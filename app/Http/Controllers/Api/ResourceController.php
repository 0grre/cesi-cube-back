<?php

namespace App\Http\Controllers\Api;

use App\Filters\ResourceFilter;
use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceResource;
use App\Models\RelationType;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResourceController extends Controller
{
    /**
     * @param ResourceFilter $resourceFilter
     * @return JsonResponse
     */
    public function index(ResourceFilter $resourceFilter): JsonResponse
    {
        if (Auth::user()) {
            if (Auth::user()->hasRole(['citizen'])) {

                $shared_resources = collect(Resource::filter($resourceFilter)->data())->filter(function ($resource) {
                    return $resource->relation_exist();
                })->whereNull('deleted_at')
                    ->where('status', 'accepted')
                    ->where('user_id', '!=' , Auth::user()->getAuthIdentifier());

                $user_resources = collect(Resource::filter($resourceFilter)->data())
                    ->whereNull('deleted_at')
                    ->where('user_id' , Auth::user()->getAuthIdentifier());

                $public_resources = collect(Resource::filter($resourceFilter)->data())->filter(function ($resource) {
                    return $resource->is_public();
                })->where('status', 'accepted')->whereNull('deleted_at');

                $test_resources = $shared_resources->merge($user_resources);
                $resources = $test_resources->merge($public_resources);
                $resources = $resources->unique();

            } else if (Auth::user()->hasRole(['moderator', 'super-admin', 'admin'])) {
                $resources = Resource::filter($resourceFilter)->data();
            }
        } else {
            $resources = collect(Resource::filter($resourceFilter)->data())->filter(function ($resource) {
                return $resource->is_public();
            })->where('status', 'accepted')->whereNull('deleted_at');
        }

        return $this->sendResponse(CollectionHelper::paginate(ResourceResource::collection($resources), 10), 'Resources found successfully.');
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
        if (Auth::user()->hasRole(['citizen'])) {
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

        if (is_null($resource)) {
            return $this->sendError('Resource not found . ');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required | string',
            'richTextContent' => 'string',
            'status' => 'string | min:2 | max:55',
            'scope' => 'string | min:2 | max:55',
            'type' => 'required',
            'category' => 'required',
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
        $resource->richTextContent = $request->richTextContent ?? $resource->richTextContent;
        $resource->mediaLink = $request->mediaLink ?? $resource->mediaLink;
        $resource->type_id = $request->type['id'] ?? $resource->type_id;
        $resource->category_id = $request->category['id'] ?? $resource->category_id;
        $resource->user_id = $id ? $resource->user_id : Auth::user()->getAuthIdentifier();

        $resource->save();

        if (!empty($request->relationTypes)) {
            foreach ($request->relationTypes as $relation_type) {
                $resource->shared()->attach(RelationType::find($relation_type['id']));
            }
        }

        return ResourceResource::make($resource);
    }
}
