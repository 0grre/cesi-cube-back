<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\User;
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
        //penser à mettre en public et faire condition en fonction du role

        $relations = DB::table('relations')
            ->where('first_user_id', '=', Auth::user()->getAuthIdentifier())
            ->orWhere('relations.second_user_id', '=', Auth::user()->getAuthIdentifier());

        $shared_resources = DB::table('resources')
            ->joinSub($relations, 'relations', function ($join) {
                $join->on('resources.user_id', '=', 'relations.first_user_id')
                    ->orOn('resources.user_id', '=', 'relations.second_user_id');
            })
            ->join('users', 'resources.user_id', '=', 'users.id')
            ->where([['resources.status', '=', 'accepted'], ['resources.scope', '=', 'shared'], ['resources.deleted_at', null]])
            ->select('resources.*',
                'users.email',
                'users.avatar',
                'users.firstname',
                'users.lastname',
                DB::raw('relations.first_user_id'),
                DB::raw('relations.second_user_id'));

        $public_resources = DB::table('resources')
            ->join('users', 'resources.user_id', '=', 'users.id')
            ->where([['resources.status', '=', 'accepted'], ['resources.deleted_at', null]])
            ->where('resources.scope', '=', 'public')
            ->orWhere([['resources.scope', '=', 'private'], ['resources.user_id', Auth::user()->getAuthIdentifier()]])
            ->union($shared_resources)
            ->select('resources.*',
                'users.email',
                'users.avatar',
                'users.firstname',
                'users.lastname',
                DB::raw('NULL as first_user_id'),
                DB::raw('NULL as second_user_id'))
            ->paginate(10);

        return $this->sendResponse($public_resources, 'Resources found successfully.');
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
        return $this->sendResponse($this->ResourceValidator($request, $id), 'Resource updated successfully.');
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

        $resource->disabled_at = date('Y-m-d H:i:s');
        $resource->save();

        return $this->sendResponse([], 'Resource deleted successfully.');
    }

    /**
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function add_to_read_later($user_id, $id): JsonResponse
    {
        $user = User::find($user_id);
        $resource = Resource::find($id);

        if (!$user->read_later()->where('resource_id', $resource->id)->exists())
        {
            $user->read_later()->attach($resource);

            return $this->sendResponse($user->read_later()->get(), 'Resource add to read later list successfully.');
        } else {

            return $this->sendError('Validation Error.', (array)'Resource read later exist');
        }
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

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $decoded = base64_decode($request->mediaUrl);
        $file = '/media';
        file_put_contents($file, $decoded);

        $resource = $id ? Resource::find($id) : new Resource();

        $resource->title = $request->title ?? $resource->title;
        $resource->views = $request->views ?? $resource->views;
        $resource->richTextContent = $request->richTextContent ?? $resource->richTextContent;
        $resource->mediaUrl = $request->mediaUrl ? Storage::url(Storage::disk('public')->putFile('medias', $file)) : $resource->mediaUrl;
        $resource->tags = $request->tags ?? $resource->tags;
        $resource->is_exploited = $request->is_exploited ?? $resource->is_exploited;
        $resource->status = $request->status ?? $resource->status;
        $resource->scope = $request->scope ?? $resource->scope;
        $resource->type_id = $request->type_id ?? $resource->type_id;
        $resource->category_id = $request->category_id ?? $resource->category_id;
        $resource->user_id = Auth::user()?->getAuthIdentifier();

        $resource->save();

        return $resource;
    }
}
