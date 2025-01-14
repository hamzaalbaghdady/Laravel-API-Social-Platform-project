<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowPostTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_view_post(): void
    {

        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('posts.show', $post);
        $response = $this->getJson($route);
        $response->assertOk();
    }

    public function test_unauthenticated_user_can_not_view_profile(): void
    {
        $post = Post::factory()->create();
        $route = Route('posts.show', $post);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }

}
