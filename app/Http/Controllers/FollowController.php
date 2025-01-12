<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        $user_to_follow = User::findOrFail($request->id);
        // syncWithoutDetaching() used to add new IDs but not remove already existing ones.
        Auth::user()->following()->syncWithoutDetaching($user_to_follow);
        return response()->json([
            'message' => 'You are following ' . $user_to_follow->first_name . ' now!'
        ]);
    }
    public function unfollow(Request $request)
    {
        $user_to_unfollow = User::findOrFail($request->id);
        // syncWithoutDetaching() used to add new IDs but not remove already existing ones.
        Auth::user()->following()->detach($user_to_unfollow);
        return response()->json([
            'message' => 'You are not following ' . $user_to_unfollow->first_name . ' anymore!'
        ]);
    }
}
