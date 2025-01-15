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

class ReactTest extends TestCase
{
    /**
     * A basic test for react creation on both posts and comments.
     */

    public function test_authenticated_user_can_react_on_post(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($profile->owner);

        $route = Route("posts.reactions", $post);
        $response = $this->postJson($route, [
            'type' => 'like',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('reactions', [
            'type' => 'like',
            'creator_id' => $profile->user_id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);
    }

    public function test_authenticated_user_can_react_on_comment(): void
    {
        $profile = Profile::factory()->create();
        $comment = Comment::factory()->create();
        Sanctum::actingAs($profile->owner);

        $route = Route("comments.reactions", $comment);
        $response = $this->postJson($route, [
            'type' => 'like',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('reactions', [
            'type' => 'like',
            'creator_id' => $profile->user_id,
            'reactable_id' => $comment->id,
            'reactable_type' => Comment::class,
        ]);
    }

    public function test_authenticated_user_reaction_updated_if_exists(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($profile->owner);

        $reaction = Reaction::factory()->create([
            'creator_id' => $profile->owner->id,
            'type' => 'love',
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);

        $route = Route("posts.reactions", $post);
        $response = $this->postJson($route, [
            'type' => 'like',
        ]);
        $response->assertOk();
        $this->assertDatabaseHas('reactions', [
            'type' => 'like',
            'creator_id' => $profile->user_id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);
        $this->assertEquals('like', $reaction->refresh()->type);
        $this->assertNotEquals($reaction->refresh()->apdated_at, $reaction->updated_at);
    }

    public function test_authenticated_user_without_profile_can_not_react(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = Route("posts.reactions", $post);
        $response = $this->postJson($route, [
            'type' => 'like',
        ]);
        $response->assertForbidden();
    }

    public function test_unauthenticated_user_can_not_react(): void
    {
        $post = Post::factory()->create();

        $route = Route("posts.reactions", $post);
        $response = $this->postJson($route, [
            'type' => 'like',
        ]);
        $response->assertUnauthorized();
    }

    public function test_reaction_type_is_vallied(): void
    {
        $profile = Profile::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($profile->owner);

        $route = Route("posts.reactions", $post);
        $response = $this->postJson($route, [
            'type' => 'exists',
        ]);
        $response->assertJsonValidationErrors(
            'type'
        );
    }
}
