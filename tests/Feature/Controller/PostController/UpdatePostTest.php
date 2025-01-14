<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Post;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePostTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_update_his_post(): void
    {

        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);
        $route = Route('posts.update', $post);
        $response = $this->putJson($route, [
            'content' => 'post update test',
        ]);
        $response->assertOk();
        $this->assertEquals('post update test', $post->refresh()->content);
    }

    public function test_unauthenticated_user_can_not_update_any_post(): void
    {
        $post = Post::factory()->create();
        $route = Route('posts.update', $post);
        $response = $this->putJson($route, [
            'content' => 'updatetest',
        ]);
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_not_update_others_post(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('posts.update', $post);
        $response = $this->putJson($route, [
            'content' => 'updatetest',
        ]);
        $response->assertForbidden();
    }

}
