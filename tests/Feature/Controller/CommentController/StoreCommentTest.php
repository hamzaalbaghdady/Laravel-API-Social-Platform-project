<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCommentTest extends TestCase
{
    /**
     * A basic test for comment creation.
     */
    public function test_authenticated_user_can_create_comment(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($profile->owner);

        $route = Route('posts.comments.store', $post);
        $response = $this->postJson($route, [
            'content' => 'This comment is for testing only',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('comments', [
            'content' => 'This comment is for testing only',
            'creator_id' => $profile->user_id,
        ]);
    }


    public function test_authenticated_user_without_profile_can_not_create_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($user);

        $route = Route('posts.comments.store', $post);
        $response = $this->postJson($route, [
            'content' => 'This comment is for testing only',
        ]);
        $response->assertForbidden();
    }

    public function test_unauthenticated_user_can_not_create_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $route = Route('posts.comments.store', $post);
        $response = $this->postJson($route, [
            'content' => 'This comments is for testing only',
        ]);
        $response->assertUnauthorized();
    }

    public function test_content_is_requierd(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($profile->owner);
        $route = Route('posts.comments.store', $post);
        $response = $this->postJson($route, []);
        $response->assertJsonValidationErrors([
            'content' => 'required',
        ]);
    }
}
