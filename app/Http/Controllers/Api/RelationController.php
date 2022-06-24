<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RelationResource;
use App\Models\Relation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelationController extends Controller
{
    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function index($user_id): JsonResponse
    {
        $relations = Relation::where('first_user_id', $user_id)
            ->orWhere('second_user_id', $user_id)->get();

        return $this->sendResponse(RelationResource::collection($relations), 'Relations retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function store(Request $request, $user_id): JsonResponse
    {
        return $this->sendResponse(self::RelationValidator($request, $user_id), 'Relation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $relation = Relation::find($id);

        if (is_null($relation)) {
            return $this->sendError('Relation not found.');
        }

        return $this->sendResponse(RelationResource::collection($relation), 'Relation found successfully.');
    }

    /**
     * Update the specified resource in storage.

     */
    public function update(Request $request, $id): JsonResponse
    {
        return $this->sendResponse(self::RelationValidator($request, $user_id = null, $id), 'Relation updated successfully.');
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $relation = Relation::find($id);

        if (is_null($relation)) {
            return $this->sendError('Relation not found.');
        }

        $relation->delete();

        return $this->sendResponse([], 'Relation deleted successfully.');
    }

    /**
     *
     * @param Request $request
     * @param null $user_id
     * @param null $id
     * @return JsonResponse
     */
    public function RelationValidator(Request $request, $user_id, $id = null): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'second_user_id' => 'required',
            'relation_type_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $relation = $id ? Relation::find($id) : new Relation();
        $relation->first_user_id = $user_id;
        $relation->second_user_id = $request->second_user_id;
        $relation->relation_type_id = $request->relation_type_id;
        $relation->save();

        return $relation;
    }
}
