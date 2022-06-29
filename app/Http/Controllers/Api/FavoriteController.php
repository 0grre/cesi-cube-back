<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceResource;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function index($user_id): JsonResponse
    {
        $user = User::find($user_id);

        return $this->sendResponse(ResourceResource::collection($user->favorites()->get()), 'Favorite resource list retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $user_id
     * @param $resource_id
     * @return JsonResponse
     */
    public function store($user_id, $resource_id): JsonResponse
    {
        $user = User::find($user_id);
        $resource = Resource::find($resource_id);

        if (!$user->favorites()->where('resource_id', $resource->id)->exists())
        {
            $user->favorites()->attach($resource);

            return $this->sendResponse(ResourceResource::collection($user->favorites()->get()), 'Resource add to favorites list successfully.');
        } else {

            return $this->sendError('Validation Error.', (array)'Resource favorites exist');
        }
    }

    /**
     *
     * @param $user_id
     * @param $resource_id
     * @return JsonResponse
     */
    public function destroy($user_id, $resource_id): JsonResponse
    {
        $user = User::find($user_id);

        $user->favorites()->detach($resource_id);

        return $this->sendResponse([], 'Resource remove to favorite list successfully.');
    }
}
