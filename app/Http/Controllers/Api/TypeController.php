<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassifyResource;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(ClassifyResource::collection(Type::all()), 'Types retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse(self::TypeValidator($request), 'Type created successfully.');
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

        return $this->sendResponse(ClassifyResource::make($type), 'Type found successfully.');
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
        return $this->sendResponse(self::TypeValidator($request, $id), 'Type updated successfully.');
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
     * @param Request $request
     * @param null $id
     * @return ClassifyResource
     */
    public function TypeValidator(Request $request, $id = null): ClassifyResource
    {

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|min:2|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $type = $id ? Type::find($id) : new Type();
        $type->name = $request->name;
        $type->save();

        return ClassifyResource::make($type);
    }
}
