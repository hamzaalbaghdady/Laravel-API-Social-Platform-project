<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|required|max:255',
            'last_name' => 'sometimes|required|max:255',
            'email' => 'sometimes|required|max:255|email|unique:users,email',
            'old_password' => 'sometimes|required|min:8|max:32',
            'password' => 'sometimes|required|min:8|max:32',
            'date_of_birth' => 'sometimes|required|date',
        ]);

        // if user know the password then can update
        if (Hash::check($validated['old_password'], Auth::user()->password)) {
            $user->update($validated);
            return response()->json([
                'message' => 'Your data has been updated successfully!'
            ]);
        }
        return response()->json([
            'message' => 'Failed: You must enter the correct password!'
        ]);
    }
    public function destroy(Request $request)
    {
        Auth::user()->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
    public function show(Request $request, User $user)
    {
        return new UserResource($user);
    }
    public function index(Request $request)
    {
        $user = QueryBuilder::for(User::class)
            ->orderByDesc('created_at')
            ->paginate();
        return new UserCollection($user);
    }
}
