<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RelationResource;
use App\Models\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function show($user_id, $id): JsonResponse
    {
        $relation = Relation::where('id', $id)->get();

        if (is_null($relation)) {
            return $this->sendError('Relation not found.');
        }

        return $this->sendResponse(RelationResource::make($relation), 'Relation found successfully.');
    }

    /**
     * @param Request $request
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $user_id, $id): JsonResponse
    {
        return $this->sendResponse(self::RelationValidator($request, $user_id, $id), 'Relation updated successfully.');
    }

    /**
     *
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function destroy($user_id, $id): JsonResponse
    {
        $relation = Relation::find($id);

        if (is_null($relation)) {
            return $this->sendError('Relation not found.');
        }

        $relation->delete();

        return $this->sendResponse([], 'Relation deleted successfully.');
    }

    /**
     * @param Request $request
     * @param $user_id
     * @param null $id
     * @return RelationResource|JsonResponse
     */
    public function RelationValidator(Request $request, $user_id, $id = null): RelationResource|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'secondUser' => 'required',
            'relationType' => 'required',
        ]);

        $relation_check = DB::table('relations')
            ->where('relation_type_id', $request->relationType)
            ->whereIn('first_user_id', [$user_id, $request->secondUser])
            ->whereIn('second_user_id', [$user_id, $request->secondUser])
            ->exists();

        if($validator->fails() or (!$id && $relation_check)){
            return $this->sendError('Validation Error.',
                $relation_check ? ['Relation with user '. $request->secondUser .' exist'] : (array)$validator->errors());
        }

        $relation = $id ? Relation::find($id) : new Relation();

        if (is_null($relation)) {
            return $this->sendError('Relation not found.');
        }

        $relation->is_accepted = $request->status == true ?? false;
        $relation->first_user_id = $user_id;
        $relation->second_user_id = $request->secondUser;
        $relation->relation_type_id = $request->relationType;
        $relation->save();

        return RelationResource::make($relation);

    }
}
