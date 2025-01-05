<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileCollection;
use App\Http\Resources\ProfileResourece;
use App\Models\Profile;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileController extends Controller
{
    public function store(StoreProfileRequest $request)
    {
        $validated = $request->validated();
        $profile = Profile::create($validated);
        return response()->json();
    }
    public function show(Request $request, Profile $profile)
    {
        return new ProfileResourece($profile);
    }
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $validated = $request->validated();
        $profile->update($validated);
        return new ProfileResourece($profile);
    }
    public function destroy(Request $request, Profile $profile)
    {
        $profile->delete();
        return response()->noContent();
    }
    public function index(Request $request)
    {
        $profiles = QueryBuilder::for(Profile::class)
            ->allowedFilters(['location'])
            ->allowedSorts(['user_name', 'location', 'created_at', 'updated_at'])
            ->defaultSort('-updated_at')
            ->paginate();
        return new ProfileCollection($profiles);
    }
}
