<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RelationRequestResource;
use App\Models\RelationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RelationRequestController extends Controller
{
    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function index($user_id): JsonResponse
    {
        $relation_requests = RelationRequest::where('first_user_id', $user_id)
            ->orWhere('second_user_id', $user_id)->get();

        return $this->sendResponse(RelationRequestResource::collection($relation_requests), 'RelationRequests retrieved successfully.');
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function store(Request $request, $user_id): JsonResponse
    {
        return $this->sendResponse(self::RelationRequestValidator($request, $user_id), 'RelationRequest created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function show($user_id, $id): JsonResponse
    {
        $relation_request = RelationRequest::where('id', $id)->get();

        if (is_null($relation_request)) {
            return $this->sendError('RelationRequest not found.');
        }

        return $this->sendResponse(RelationRequestResource::make($relation_request), 'RelationRequest found successfully.');
    }

    /**
     * @param Request $request
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $user_id, $id): JsonResponse
    {
        return $this->sendResponse(self::RelationRequestValidator($request, $user_id, $id), 'RelationRequest updated successfully.');
    }

    /**
     *
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function destroy($user_id, $id): JsonResponse
    {
        $relation_request = RelationRequest::find($id);

        if (is_null($relation_request)) {
            return $this->sendError('RelationRequest not found.');
        }

        $relation_request->delete();

        return $this->sendResponse([], 'RelationRequest deleted successfully.');
    }


    /**
     * @param Request $request
     * @param $user_id
     * @param null $id
     * @return JsonResponse|RelationRequestResource
     */
    public function RelationRequestValidator(Request $request, $user_id, $id = null): JsonResponse|RelationRequestResource
    {

        $relation_request = DB::table('relation_requests')
            ->whereIn('first_user_id', [$user_id, $request->second_user_id])
            ->whereIn('second_user_id', [$user_id, $request->second_user_id])
            ->exists();

        $validator = Validator::make($request->all(), [
            'second_user_id' => 'required',
            'relation_type_id' => 'required',
        ]);

        if($validator->fails() or (!$id && $relation_request) or ($user_id == $request->second_user_id)){
            return $this->sendError('Validation Error.',
                $relation_request ? ['RelationRequest with user '. $request->second_user_id .' exist'] : (array)$validator->errors());
        }

        $relation_request = $id ? RelationRequest::find($id) : new RelationRequest();
        $relation_request->first_user_id = $user_id;
        $relation_request->second_user_id = $request->second_user_id;
        $relation_request->relation_type_id = $request->relation_type_id;
        $relation_request->save();

        return RelationRequestResource::make($relation_request);
    }
}
