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

class UpdateCommentTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_update_his_comment(): void
    {
        $profile = Profile::factory()->create();// its not necessary for update but commentResource
        $comment = Comment::factory()->create(['creator_id' => $profile->user_id]);
        $post = Post::factory()->create();
        Sanctum::actingAs($comment->creator);

        $route = Route('posts.comments.update', [$post, $comment]);
        $response = $this->putJson($route, [
            'content' => 'comment update test',
        ]);

        $response->assertOk();
        // $this->assertEquals('comment update test', $comment->refresh()->content);
    }

    public function test_unauthenticated_user_can_not_update_any_comment(): void
    {
        $comment = Comment::factory()->create();
        $post = Post::factory()->create();
        $route = Route('posts.comments.update', [$post, $comment]);
        $response = $this->putJson($route, [
            'content' => 'updatetest',
        ]);
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_not_update_others_comment(): void
    {
        $comment = Comment::factory()->create();
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('posts.comments.update', [$post, $comment]);
        $response = $this->putJson($route, [
            'content' => 'updatetest',
        ]);
        $response->assertForbidden();
    }

}
