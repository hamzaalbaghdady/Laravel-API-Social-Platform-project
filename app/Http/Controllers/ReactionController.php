<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ReactionController extends Controller
{
    public function __construct()
    {
        Route::bind('post', function ($value) {
            return Post::findOrFail($value);
        });
        Route::bind('comment', function ($value) {
            return Comment::findOrFail($value);
        });
    }

    public function react(Request $request, Post|Comment $model)
    {
        $validated = $request->validate([
            'type' => "required|string|in:like,love,haha,wow,care,sad,angry",
        ]);

        //Check if the user has already react
        $existingReaction = Reaction::where('creator_id', auth()->id())
            ->where('reactable_type', $model->getMorphClass())
            ->where('reactable_id', $model->id)
            ->first();

        if ($existingReaction) {
            // Update if already reacted
            $model->reaction()->update($validated);
            $message = 'Reaction has updated successfully!';

        } else {
            // create react if first time
            $reaction = $model->reaction()->make($validated);
            $reaction->creator()->associate(Auth::user());
            $reaction->save();
            $message = 'Reaction saved successfully!';
        }

        return response()->json([
            'message' => $message,
        ]);
    }

    public function unreact(Post|Comment $model, Reaction $reaction)
    {
        $reaction->delete();
        return response()->json([
            'message' => 'Reaction has deleted successfully!',
        ]);
    }
}
