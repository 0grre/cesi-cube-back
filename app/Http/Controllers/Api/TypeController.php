<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TypeCategoryResource;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TypeController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(
            TypeCategoryResource::collection(Type::all()), 'Types retrieved successfully.');
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
        return $this->sendResponse(new TypeCategoryResource(
            self::TypeValidator($request)), 'Type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {

        $type = Type::find($id);

        if (is_null($type)) {
            return $this->sendError('Type not found.');
        }

        return $this->sendResponse(
            TypeCategoryResource::collection($type), 'Type retrieved successfully.');
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
        return $this->sendResponse(new TypeCategoryResource(
            self::TypeValidator($request, $id)), 'Type updated successfully.');
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        Type::find($id)->delete();

        return $this->sendResponse([], 'Type deleted successfully.');
    }

    /**
     * @return void
     * @throws ValidationException
     */
    public function TypeValidator(Request $request, $id = null){
        Validator::make($request->all(), [
            'content' => 'required|string|min:2|max:255',
        ])->validate();

        $type = $id ? Type::find($id) : new Type();
        $type->label = $request->label;
        $type->save();

        return $type;
    }
}
