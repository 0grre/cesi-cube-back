<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ReadLaterController extends Controller
{
    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function index($user_id): JsonResponse
    {
        $user = User::find($user_id);

        return $this->sendResponse($user->read_later()->get(), 'Read later resource list retrieved successfully.');
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

        if (!$user->read_later()->where('resource_id', $resource->id)->exists())
        {
            $user->read_later()->attach($resource);

            return $this->sendResponse($user->read_later()->get(), 'Resource add to read later list successfully.');
        } else {

            return $this->sendError('Validation Error.', (array)'Resource read_later exist');
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

        $user->read_later()->detach($resource_id);

        return $this->sendResponse([], 'Resource remove to read later list successfully.');
    }
}
