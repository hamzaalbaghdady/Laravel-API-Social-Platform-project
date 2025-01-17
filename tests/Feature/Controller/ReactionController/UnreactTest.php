<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Comment;
use App\Models\Reaction;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnreactTest extends TestCase
{
    /**
     * A basic test for react deletion on both posts and comments.
     */

    public function test_authenticated_user_can_unreact_on_post(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        $reaction = Reaction::factory()->create([
            'creator_id' => $profile->owner->id,
            'type' => 'love',
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);
        Sanctum::actingAs($profile->owner);

        $route = Route("posts.reactions.unreact", [$post, $reaction]);
        $response = $this->deleteJson($route);
        $response->assertok();
        $this->assertDatabaseMissing('reactions', [
            'creator_id' => $profile->user_id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);
    }

    public function test_authenticated_user_can_unreact_on_comment(): void
    {
        $profile = Profile::factory()->create();
        $comment = Comment::factory()->create();
        $reaction = Reaction::factory()->create([
            'creator_id' => $profile->owner->id,
            'type' => 'love',
            'reactable_id' => $comment->id,
            'reactable_type' => Comment::class,
        ]);
        Sanctum::actingAs($profile->owner);

        $route = Route("comments.reactions.unreact", [$comment, $reaction]);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('reactions', [
            'creator_id' => $profile->user_id,
            'reactable_id' => $comment->id,
            'reactable_type' => Comment::class,
        ]);
    }
}
