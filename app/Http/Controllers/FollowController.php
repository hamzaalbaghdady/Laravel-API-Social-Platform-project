<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        $user = Auth::user();
        // Check if the user has a profile
        if (!$user->profile()->exists()) {
            return response()->json([
                'message' => 'User should have a profile to follow others!'
            ], 403);
        }

        $user_to_follow = Profile::findOrFail($request->id)->owner;

        // Check if user tries to follow himself
        if ($user->id == $user_to_follow->id) {
            return response()->json([
                'message' => 'You can not follow your self!'
            ], 403);
        }
        // syncWithoutDetaching() used to add new IDs but not remove already existing ones.
        $user->following()->syncWithoutDetaching($user_to_follow);
        return response()->json([
            'message' => 'You are following ' . $user_to_follow->first_name . ' now!'
        ]);
    }
    public function unfollow(Request $request)
    {
        $user_to_unfollow = Profile::findOrFail($request->id)->owner;
        // syncWithoutDetaching() used to add new IDs but not remove already existing ones.
        Auth::user()->following()->detach($user_to_unfollow);
        return response()->json([
            'message' => 'You are not following ' . $user_to_unfollow->first_name . ' anymore!'
        ]);
    }
}
