<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassifyResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(ClassifyResource::collection(Category::all()), 'Categories retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendResponse(self::CategoryValidator($request), 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {

        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }

        return $this->sendResponse(ClassifyResource::make($category), 'Category found successfully.');
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
        return $this->sendResponse(self::CategoryValidator($request, $id), 'Category updated successfully.');
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        Category::find($id)->delete();

        return $this->sendResponse([], 'Category deleted successfully.');
    }

    /**
     * @param Request $request
     * @param null $id
     * @return JsonResponse|ClassifyResource
     */
    public function CategoryValidator(Request $request, $id = null): JsonResponse|ClassifyResource
    {

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|min:2|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $category = $id ? Category::find($id) : new Category();
        $category->name = $request->name;
        $category->save();

        return ClassifyResource::make($category);
    }
}
