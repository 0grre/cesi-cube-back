<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * @param $id
     * @return JsonResponse
     */
    public function index($id): JsonResponse
    {
        $comments = Resource::find($id)->comments()->get();

        return $this->sendResponse($comments, 'Comments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $resource_id
     * @return JsonResponse
     */
    public function store(Request $request, $resource_id): JsonResponse
    {
        return $this->sendResponse(self::CommentValidator($request, $resource_id), 'Comment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {

        $comment = Comment::find($id);

        if (is_null($comment)) {
            return $this->sendError('Comment not found.');
        }

        return $this->sendResponse($comment, 'Comment found successfully.');
    }

    /**
     * Update the specified resource in storage.

     */
    public function update(Request $request, $resource_id, $id): JsonResponse
    {
        return $this->sendResponse(self::CommentValidator($request, $resource_id, $id), 'Comment updated successfully.');
    }

    /**
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        Comment::find($id)->delete();

        return $this->sendResponse([], 'Comment deleted successfully.');
    }

    /**
     * @param Request $request
     * @param null $resource_id
     * @param null $id
     * @return Comment|JsonResponse
     */
    public function CommentValidator(Request $request, $resource_id = null, $id = null): Comment|JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:2|max:255',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $comment = $id ? Comment::find($id) : new Comment();
        $comment->content = request('content');
        $comment->resource_id = $resource_id;
        $comment->user_id = Auth::user()->getAuthIdentifier();
        $comment->save();

        return $comment;
    }
}
