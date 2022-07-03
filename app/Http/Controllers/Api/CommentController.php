<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ResourceResource;
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

        return $this->sendResponse(CommentResource::collection($comments), 'Comments retrieved successfully.');
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $resource_id, $id): JsonResponse
    {
        $comment = Comment::find($id);

        if (is_null($comment)) {
            return $this->sendError('Comment not found.');
        }

        if (self::check_owner($comment)) {
            return $this->sendResponse(self::CommentValidator($request, $resource_id, $id), 'Comment updated successfully.');
        } else {
            return $this->sendError('Validation Error.', (array)'this comment does not belong to you');
        }
    }

    /**
     *
     * @param $resource_id
     * @param $id
     * @return JsonResponse
     */
    public function destroy($resource_id, $id): JsonResponse
    {
        $resource = Resource::find($resource_id);

        if (is_null($resource)) {
            return $this->sendError('Resource not found.');
        }

        $comment = Comment::find($id);

        if (is_null($comment)) {
            return $this->sendError('Comment not found.');
        }

        if (self::check_owner($comment)) {

            $comment->delete();

            return $this->sendResponse(ResourceResource::make($resource), 'Comment deleted successfully.');
        } else {
            return $this->sendError('Validation Error.', (array)'this comment does not belong to you');
        }
    }

    /**
     * @param $comment
     * @return bool
     */
    public function check_owner($comment): bool
    {
        if (Auth::user()->hasRole('citizen')) {
            if ($comment->user_id != Auth::user()->getAuthIdentifier()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * @param Request $request
     * @param null $resource_id
     * @param null $id
     * @return JsonResponse|ResourceResource
     */
    public function CommentValidator(Request $request, $resource_id, $id = null): JsonResponse|ResourceResource
    {
        $resource = Resource::find($resource_id);

        if (is_null($resource)) {
            return $this->sendError('Resource not found.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', (array)$validator->errors());
        }

        $comment = $id ? Comment::find($id) : new Comment();
        $comment->content = request('content');
        $comment->resource_id = $resource->id;
        $comment->user_id = $id ? $comment->user_id : Auth::user()->getAuthIdentifier();
        $comment->save();

        return ResourceResource::make($resource);
    }
}
