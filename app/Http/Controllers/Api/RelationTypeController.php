<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RelationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelationTypeController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(RelationType::all(), 'RelationTypes retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse(self::RelationTypeValidator($request), 'RelationType created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {

        $relation_type = RelationType::find($id);

        if (is_null($relation_type)) {
            return $this->sendError('RelationType not found.');
        }

        return $this->sendResponse(self::RelationTypeValidator($relation_type), 'RelationType found successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        return $this->sendResponse(self::RelationTypeValidator($request, $id), 'RelationType updated successfully.');
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        RelationType::find($id)->delete();

        return $this->sendResponse([], 'RelationType deleted successfully.');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return RelationType|JsonResponse
     */
    public function RelationTypeValidator(Request $request, $id = null): RelationType|JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:55',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $relation_type = $id ? RelationType::find($id) : new RelationType();
        $relation_type->name = $request->name;
        $relation_type->save();

        return $relation_type;
    }
}
