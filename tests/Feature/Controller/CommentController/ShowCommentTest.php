<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCommentTest extends TestCase
{
    /**
     * test for comment retrival.
     */
    public function test_authenticated_user_can_view_comments(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create(['creator_id' => $profile->user_id]);
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('posts.comments.show', [$post, $comment]);
        $response = $this->getJson($route);
        $response->assertOk();
    }

    public function test_unauthenticated_user_can_not_view_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();

        $route = Route('posts.comments.show', [$post, $comment]);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }

}
