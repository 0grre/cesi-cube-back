<?php

namespace App\Http\Controllers\Api;

use App\Filters\ResourceFilter;
use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceResource;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResourceController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        if (Auth::user()) {
            if (Auth::user()->hasRole(['citizen', 'moderator'])) {
                $user_resources = DB::table('resources')
                    ->whereNull('resources.deleted_at')
                    ->where(function($query) {
                        $query->where([
                            ['resources.status', '=', 'accepted'],
                            ['resources.scope', '=', 'public']
                        ])->orWhere('resources.user_id', Auth::user()->getAuthIdentifier());
                    })
                    ->join('users', 'resources.user_id', '=', 'users.id')
                    ->select('resources.*');

                $relations = DB::table('relations')
                    ->where('first_user_id', '=', Auth::user()->getAuthIdentifier())
                    ->orWhere('relations.second_user_id', '=', Auth::user()->getAuthIdentifier());

                $shared_resources = Resource::joinSub($relations, 'relations', function ($join) {
                    $join->on('resources.user_id', '=', 'relations.first_user_id')
                        ->orOn('resources.user_id', '=', 'relations.second_user_id');
                })
                    ->join('users', 'resources.user_id', '=', 'users.id')
                    ->whereNull('resources.deleted_at')
                    ->where([
                        ['resources.scope', '=', 'shared'],
                        ['resources.status', '=', 'accepted']
                    ])->union($user_resources)
                    ->select('resources.*')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $resources = $shared_resources;

            } else if (Auth::user()->hasRole(['super-admin', 'admin'])) {
                $resources = Resource::orderBy('created_at', 'desc')->get();
            }
        } else {
            $resources = Resource::where([
                ['resources.status', '=', 'accepted'],
                ['resources.scope', '=', 'public'],
                ['resources.deleted_at', null]
            ])->orderBy('created_at', 'desc')
                ->get();
        }

        return $this->sendResponse(CollectionHelper::paginate(ResourceResource::collection(collect($resources)), 10), 'Resources found successfully.');
    }

    /**
     * @param ResourceFilter $resourceFilter
     * @return LengthAwarePaginator
     */
    public function search(ResourceFilter $resourceFilter): LengthAwarePaginator
    {
        $resourceFilterResult = Resource::filter($resourceFilter);

        return CollectionHelper::paginate(ResourceResource::collection($resourceFilterResult->data()), 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse($this->ResourceValidator($request), 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $resource = Resource::find($id);

        if (is_null($resource)) {
            return $this->sendError('Resource not found.');
        }

        if (Auth::user()) {
            if (!self::check_owner($resource) && $resource->scope == 'private') {
                return $this->sendError('Resource is private.');
            }
        } else if ($resource->scope != 'public') {
            return $this->sendError('Resource is not accessible');
        }

        $resource->views += 1;
        $resource->save();

        return $this->sendResponse(ResourceResource::make($resource), 'Resource found successfully . ');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        $resource = Resource::find($id);

        if (is_null($resource)) {
            return $this->sendError('Resource not found . ');
        }

        if (self::check_owner($resource)) {
            return $this->sendResponse($this->ResourceValidator($request, $id), 'Resource updated successfully . ');
        } else {
            return $this->sendError('Validation Error . ', (array)'this resource does not belong to you');
        }
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $resource = Resource::find($id);

        if (is_null($resource)) {
            return $this->sendError('Resource not found . ');
        }

        if (self::check_owner($resource)) {

            $resource->deleted_at = Carbon::now();
            $resource->save();

            return $this->sendResponse([], 'Resource deleted successfully . ');
        } else {
            return $this->sendError('Validation Error . ', (array)'this resource does not belong to you');
        }
    }

    /**
     * @param $resource
     * @return bool
     */
    public function check_owner($resource): bool
    {
        if (Auth::user()->hasRole(['citizen', 'moderator'])) {
            if ($resource->user_id != Auth::user()->getAuthIdentifier()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * @param Request $request
     * @param null $id
     * @return JsonResponse|ResourceResource
     */
    public function ResourceValidator(Request $request, $id = null): JsonResponse|ResourceResource
    {
        $resource = $id ? Resource::find($id) : new Resource();

        $validator = Validator::make($request->all(), [
            'title' => 'required | string',
            'views' => 'integer',
            'richTextContent' => 'string',
            'mediaUrl' => 'string',
            'status' => 'string | min:2 | max:55',
            'scope' => 'string | min:2 | max:55',
//            'type' => 'required',
//            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error . ', (array)$validator->errors());
        }

        if(!empty($request->mediaUrl) && !str_starts_with($request->mediaUrl, '/storage')){
            $decoded = base64_decode($request->mediaUrl);
            $file = 'media';
            file_put_contents($file, $decoded);
            $resource->mediaUrl = Storage::url(Storage::disk('public')->putFile('medias', $file));
        } elseif (!empty($request->mediaUrl)) {
            $resource->mediaUrl = $request->mediaUrl;
        } else {
            $resource->mediaUrl = null;
        }

        if (!$id) {
            $resource->status = 'pending';
        } else if ($request->status) {
            $resource->status = $request->status;
        }

        $resource->title = $request->title ?? $resource->title;
        $resource->views = $request->views ?? $resource->views;
        $resource->richTextContent = $request->richTextContent ?? $resource->richTextContent;
        $resource->scope = $request->scope ?? $resource->scope;
        $resource->type_id = $request->type['id'] ?? $resource->type_id;
        $resource->category_id = $request->category['id'] ?? $resource->category_id;
        $resource->user_id = $id ? $resource->user_id : Auth::user()->getAuthIdentifier();

        $resource->save();

        return ResourceResource::make($resource);
    }
}
