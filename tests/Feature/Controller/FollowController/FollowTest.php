<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowTest extends TestCase
{
    /**
     * A basic test for Follows creation.
     */
    public function test_authenticated_user_can_follow_others(): void
    {
        $follower_profile = Profile::factory()->create();
        $following_profile = Profile::factory()->create();
        Sanctum::actingAs($follower_profile->owner);

        $route = Route('follow', $following_profile->id);
        $response = $this->postJson($route);
        $response->assertOk();
        $this->assertDatabaseHas('follows', [
            'follower_id' => $follower_profile->owner->id,
            'following_id' => $following_profile->owner->id,
        ]);
    }

    public function test_authenticated_user_without_profile_can_not_follow(): void
    {
        $follower = User::factory()->create();
        $following_profile = Profile::factory()->create();
        Sanctum::actingAs($follower);

        $route = Route('follow', $following_profile->id);
        $response = $this->postJson($route);
        $response->assertForbidden();
    }

    public function test_authenticated_user_can_not_follow_himself(): void
    {
        $follower_profile = Profile::factory()->create();
        Sanctum::actingAs($follower_profile->owner);

        $route = Route('follow', $follower_profile->id);
        $response = $this->postJson($route);
        $response->assertForbidden();
    }

    public function test_unauthenticated_user_can_not_follow_others(): void
    {
        $follower_profile = Profile::factory()->create();
        $following_profile = Profile::factory()->create();

        $route = Route('follow', $following_profile->id);
        $response = $this->postJson($route);
        $response->assertUnauthorized();
    }

}
