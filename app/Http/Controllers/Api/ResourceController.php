<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return $this->sendResponse(Resource::all(), 'Resources found successfully.');;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse(self::ResourceValidator($request), 'Resource created successfully.');
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

        return $this->sendResponse($resource, 'Resource found successfully.');
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
        return $this->sendResponse(self::ResourceValidator($request, $id), 'Resource updated successfully.');
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

        return $this->sendResponse([], 'Resource deleted successfully.');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return Resource|JsonResponse
     * @throws ValidationException
     */
    public function ResourceValidator(Request $request, $id = null): Resource|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'views' => 'integer',
            'richTextContent' => 'string',
            'mediaUrl' => 'max:10000',
            'tags' => 'string|min:2|max:55',
            'is_exploited' => 'boolean',
            'status' => 'string|min:2|max:55',
            'scope' => 'string|min:2|max:55',
            'type_id' => 'required|integer',
            'category_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $resource = $id ? Resource::find($id) : new Resource();
        $resource->title = $request->title;
        $resource->views = $request->views;
        $resource->richTextContent = $request->richTextContent;
        $resource->tags = $request->tags;
        $resource->is_exploited = $request->is_exploited;
        $resource->status = $request->status;
        $resource->scope = $request->scope;

        if(!empty($request->medialUrl)){
            $resource->mediaUrl = Storage::url(Storage::disk('public')->put('medias', $request->mediaUrl));
        }

        $resource->type_id = $request->type_id;
        $resource->category_id = $request->category_id;
        $resource->user_id = Auth::user()->getAuthIdentifier();

        $resource->save();

        return $resource;
    }
}
