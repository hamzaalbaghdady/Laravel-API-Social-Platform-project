<?php

namespace Tests\Unit\Models;

use App\Models\Reaction;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;


class PostTest extends TestCase
{

    public function test_profile_create_posts()
    {

        $profile = Profile::factory()->create();
        $post = $profile->posts()->make([
            'content' => 'new post'
        ]);
        $post->creator()->associate($profile->owner);
        $post->save();
        $this->assertModelExists($post);
    }

    public function test_featch_comments_on_post()
    {
        $post = Post::factory()->create();
        $comments = Comment::factory()->count(3)->create(['post_id' => $post->id]);
        $this->assertCount(3, $post->comments);
        $this->assertInstanceOf(Comment::class, $post->comments()->first());
    }
    public function test_featch_reactions_on_post()
    {
        $post = Post::factory()->create();
        $reactions = Reaction::factory()->count(3)->create([
            'reactable_id' => $post->id,
            'reactable_type' => Post::class
        ]);
        $this->assertCount(3, $post->reaction);
        $this->assertInstanceOf(Reaction::class, $post->reaction()->first());
    }
}
