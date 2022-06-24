<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RelationResource;
use App\Models\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

        return $this->sendResponse(RelationResource::collection($relation), 'Relation found successfully.');
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
     * @param $id
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function RelationValidator(Request $request, $user_id, $id = null): JsonResponse|AnonymousResourceCollection
    {

        $relation = DB::table('relations')
            ->whereIn('first_user_id', [$user_id, $request->second_user_id])
            ->whereIn('second_user_id', [$user_id, $request->second_user_id])
            ->exists();

        $validator = Validator::make($request->all(), [
            'second_user_id' => 'required',
            'relation_type_id' => 'required',
        ]);

        if($validator->fails() or (!$id && $relation)){
            return $this->sendError('Validation Error.',
                $relation ? ['Relation with user '. $request->second_user_id .' exist'] : (array)$validator->errors());
        }

        $relation = $id ? Relation::find($id) : new Relation();
        $relation->first_user_id = $user_id;
        $relation->second_user_id = $request->second_user_id;
        $relation->relation_type_id = $request->relation_type_id;
        $relation->save();

        return RelationResource::collection(Relation::where('id', $relation->id)->get());
    }
}
