<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyCommentTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_delete_his_comments(): void
    {

        $comment = Comment::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($comment->creator);

        $route = Route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
            'creator_id' => $comment->creator_id,
            'content' => $comment->content,
        ]);
    }

    public function test_unauthenticated_user_can_not_delete_any_comment(): void
    {
        $comment = Comment::factory()->create();
        $post = Post::factory()->create();
        $route = Route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_not_delete_others_comment(): void
    {
        $comment = Comment::factory()->create();
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertForbidden();
    }

    public function test_post_creator_can_delete_any_post(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->creator);
        $comment = Comment::factory()->for($post)->create();

        $route = Route('posts.comments.destroy', [$post, $comment]);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
            'creator_id' => $comment->creator_id,
            'content' => $comment->content,
        ]);
    }

}
