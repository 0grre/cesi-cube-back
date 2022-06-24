<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RelationController extends Controller
{
    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function index($user_id): JsonResponse
    {
        $user_relations = User::find($user_id)->relations()->get();
        $user2_relations = User::find($user_id)->relations()->get();

        return $this->sendResponse($relations, 'Relations retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $user_id
     * @param $user2_id
     * @return JsonResponse
     */
    public function store(Request $request, $user_id, $user2_id): JsonResponse
    {
        return $this->sendResponse(self::RelationValidator($request, $user_id, $user2_id), 'Relation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $user_id
     * @param $user2_id
     * @return JsonResponse
     */
    public function show($user_id, $user2_id): JsonResponse
    {

        $user = User::find($user_id);

        $relations = $user->relations()->where('user2-id', $user2_id)->get();

        if (is_null($relations)) {
            return $this->sendError('Relation not found.');
        }

        return $this->sendResponse($relations, 'Relation found successfully.');
    }

    /**
     * Update the specified resource in storage.

     */
    public function update(Request $request, $user_id, $user2_id): JsonResponse
    {
        return $this->sendResponse(self::RelationValidator($request, $user_id, $user2_id), 'Relation updated successfully.');
    }

    /**
     *
     * @param $user_id
     * @param $user2_id
     * @return JsonResponse
     */
    public function destroy($user_id, $user2_id): JsonResponse
    {
        $user = User::find($user_id);

        $user->relations()->detach($user2_id);

        return $this->sendResponse([], 'Relation deleted successfully.');
    }

    /**
     *
     * @param Request $request
     * @param null $user_id
     * @param $user2_id
     * @return JsonResponse
     */
    public function RelationValidator(Request $request, $user_id, $user2_id): JsonResponse
    {
        $user = User::find($user_id);

        $validator = Validator::make($request->all(), [
            'relation_type_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $user->relations()->sync([
            $user2_id => ['relation_type_id' => $request->relation_type_id]
        ]);

        return $user->relations()->first();
    }
}
