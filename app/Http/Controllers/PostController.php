<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if the user has a profile
        if (!$user->profile()->exists()) {
            return response()->json([
                'message' => 'User should have a profile to make posts!'
            ], 403);
        }

        $profile = $user->profile;

        // Create a new post
        $post = new Post($validated);
        // $post->profile_id = $profile->id; // Explicitly set the profile_id
        // $post->creator_id = $user->id; // Explicitly set the profile_id
        // This way is better than Explicitly, keep ORM in charge ;)
        $post->creator()->associate($user);
        $post->profile()->associate($profile);
        $post->save();

        return new PostResource($post);
    }
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'sometimes|required',
            'image' => 'sometimes|nullable',
        ]);
        $post->update($validated);
        return new PostResource($post);
    }
    public function destroy(Request $request, Post $post)
    {
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully!'
        ]);
    }
    public function show(Request $request, Post $post)
    {
        return new PostResource($post);
    }
    public function index(Request $request)
    {

        $post = QueryBuilder::for(Post::class)
            ->allowedFilters('creator_id')
            ->allowedSorts(['content', 'created_at', 'updated_at'])
            ->defaultSort('-updated_at')
            ->paginate();
        return new PostCollection($post);
    }
}
