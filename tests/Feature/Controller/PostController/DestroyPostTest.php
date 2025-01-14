<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyPostTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_delete_his_posts(): void
    {

        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);

        $route = Route('posts.destroy', $post);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'creator_id' => $post->creator_id,
            'content' => $post->content,
        ]);
    }

    public function test_unauthenticated_user_can_not_delete_any_post(): void
    {
        $post = Post::factory()->create();
        $route = Route('posts.destroy', $post);
        $response = $this->deleteJson($route);
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_not_delete_others_post(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('posts.destroy', $post);
        $response = $this->deleteJson($route);
        $response->assertForbidden();
    }

}
