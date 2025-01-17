<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnfollowTest extends TestCase
{
    /**
     * A basic test for Follows deletion.
     */
    public function test_authenticated_user_can_unfollow_others(): void
    {
        $follower_profile = Profile::factory()->create();
        $following_profile = Profile::factory()->create();
        Sanctum::actingAs($follower_profile->owner);

        $route = Route('unfollow', $following_profile->id);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('follows', [
            'follower_id' => $follower_profile->owner->id,
            'following_id' => $following_profile->owner->id,
        ]);
    }



}
