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
        return response()->json(Type::all())->withCookie('success', 'Types retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(self::TypeValidator($request))->withCookie('success', 'Type created successfully.');
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

        return response()->json(self::TypeValidator($type))->withCookie('success', 'Type found successfully.');
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
        return response()->json(self::TypeValidator($request, $id))->withCookie('success', 'Type updated successfully.');
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
     * @return Type|JsonResponse
     */
    public function TypeValidator(Request $request, $id = null): Type|JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|min:2|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $type = $id ? Type::find($id) : new Type();
        $type->label = $request->label;
        $type->save();

        return $type;
    }
}
