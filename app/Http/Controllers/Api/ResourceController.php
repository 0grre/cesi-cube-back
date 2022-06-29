<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceResource;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
                    ->join('users', 'resources.user_id', '=', 'users.id')
                    ->where([['resources.status', '=', 'accepted'], ['resources.deleted_at', null]])
                    ->where('resources.scope', '=', 'public')
                    ->orWhere('resources.user_id', Auth::user()->getAuthIdentifier())
                    ->select('resources.*');

                $relations = DB::table('relations')
                    ->where('first_user_id', '=', Auth::user()->getAuthIdentifier())
                    ->orWhere('relations.second_user_id', '=', Auth::user()->getAuthIdentifier());

                $shared_resources = Resource::joinSub($relations, 'relations', function ($join) {
                        $join->on('resources.user_id', '=', 'relations.first_user_id')
                            ->orOn('resources.user_id', '=', 'relations.second_user_id');
                    })
                    ->join('users', 'resources.user_id', '=', 'users.id')
                    ->where([['resources.status', '=', 'accepted'], ['resources.scope', '=', 'shared'], ['resources.deleted_at', null]])
                    ->union($user_resources)
                    ->select('resources.*')
                    ->get();

                $resources = $shared_resources;

            } else if (Auth::user()->hasRole(['super-admin', 'admin'])) {
                $resources = DB::table('resources')
                    ->select('resources.*')
                    ->get();
            }
        } else {
            $resources = DB::table('resources')
                ->join('users', 'resources.user_id', '=', 'users.id')
                ->where([['resources.status', '=', 'accepted'], ['resources.deleted_at', null]])
                ->where('resources.scope', '=', 'public')
                ->select('resources.*')
                ->get();
        }

        return $this->sendResponse(ResourceResource::collection(collect($resources)), 'Resources found successfully.');
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

        if (!self::check_owner($resource) && $resource->scope == 'private')
        {
             return $this->sendError('Resource is private.');
        }

        if (is_null($resource)) {
            return $this->sendError('Resource not found.');
        }

        return $this->sendResponse(ResourceResource::make($resource), 'Resource found successfully.');
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
            return $this->sendError('Resource not found.');
        }

        if (self::check_owner($resource)) {
            return $this->sendResponse($this->ResourceValidator($request, $id), 'Resource updated successfully.');
        } else {
            return $this->sendError('Validation Error.', (array)'this resource does not belong to you');
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
            return $this->sendError('Resource not found.');
        }

        if (self::check_owner($resource)) {

            $resource->disabled_at = date('Y-m-d H:i:s');
            $resource->save();

            return $this->sendResponse([], 'Resource deleted successfully.');
        } else {
            return $this->sendError('Validation Error.', (array)'this resource does not belong to you');
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'views' => 'integer',
            'richTextContent' => 'string',
            'mediaUrl' => 'max:10000',
            'status' => 'string|min:2|max:55',
            'scope' => 'string|min:2|max:55',
            'type_id' => 'required|integer',
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        if ($request->mediaUrl) {
            $decoded = base64_decode($request->mediaUrl);
            $file = '/media';
            file_put_contents($file, $decoded);
        }

        $resource = $id ? Resource::find($id) : new Resource();

        $resource->title = $request->title ?? $resource->title;
        $resource->views = $request->views ?? $resource->views;
        $resource->richTextContent = $request->richTextContent ?? $resource->richTextContent;
        $resource->mediaUrl = $request->mediaUrl ? Storage::url(Storage::disk('public')->putFile('medias', $file)) : $resource->mediaUrl;
        $resource->status = $request->status ?? $resource->status;
        $resource->scope = $request->scope ?? $resource->scope;
        $resource->type_id = $request->type_id ?? $resource->type_id;
        $resource->category_id = $request->category_id ?? $resource->category_id;
        $resource->user_id = $id ? $resource->user_id : Auth::user()?->getAuthIdentifier();

        $resource->save();

        return ResourceResource::make($resource);
    }
}
