<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorePostTest extends TestCase
{
    /**
     * A basic test for posts creation.
     */
    public function test_authenticated_user_can_create_posts(): void
    {
        $profile = Profile::factory()->create();
        Sanctum::actingAs($profile->owner);

        $route = Route('posts.store');
        $response = $this->postJson($route, [
            'content' => 'This post is for testing only',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('posts', [
            'content' => 'This post is for testing only',
            'creator_id' => $profile->user_id,
        ]);
    }


    public function test_authenticated_user_without_profile_can_not_create_posts(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = Route('posts.store');
        $response = $this->postJson($route, [
            'content' => 'This post is for testing only',
        ]);
        $response->assertForbidden();
    }

    public function test_unauthenticated_user_can_not_create_profile(): void
    {
        $user = User::factory()->create();
        $route = Route('posts.store');
        $response = $this->postJson($route, [
            'content' => 'This post is for testing only',
        ]);
        $response->assertUnauthorized();
    }

    public function test_content_is_requierd(): void
    {
        $profile = Profile::factory()->create();
        Sanctum::actingAs($profile->owner);
        $route = Route('posts.store');
        $response = $this->postJson($route, []);
        $response->assertJsonValidationErrors([
            'content' => 'required',
        ]);
    }
}
