<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();
        // Check if the user has a profile
        if (!$user->profile()->exists()) {
            return response()->json([
                'message' => 'User must have a profile to make comments!'
            ], 403);
        }
        $validated = $request->validate([
            'content' => 'required',
            'attachment' => 'nullable',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);
        $comment = $post->comments()->make($validated);
        $comment->creator()->associate($user);
        $comment->save();
        return new CommentResource($comment);
    }
    public function update(Request $request, Post $post, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required',
            'attachment' => 'nullable',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);
        $comment->update($validated);
        return new CommentResource($comment);
    }
    public function destroy(Request $request, Post $post, Comment $comment)
    {
        $comment->delete();
        return response()->json([
            'message' => 'Comment deleted successfully!',
        ]);
    }
    public function show(Request $request, Post $post, Comment $comment)
    {
        return new CommentResource($comment);
    }
    public function index(Request $request, Post $post)
    {
        // retrive all comments for specified post
        $comment = QueryBuilder::for($post->comments())
            ->where('post_id', '=', $post->id)
            ->allowedFilters(['reply_id'])
            ->allowedSorts(['content', 'created_at', 'updated_at'])
            ->defaultSort('-updated_at')
            ->paginate();
        return new CommentCollection($comment);
    }
}
