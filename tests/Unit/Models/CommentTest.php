<?php

namespace Tests\Unit\Models;

use App\Models\Reaction;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;


class CommentTest extends TestCase
{

    public function test_user_create_comments()
    {

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create();

        $comment = $user->comments()->make([
            'content' => 'new post'
        ]);
        $comment->post()->associate($post);
        $comment->save();

        $this->assertModelExists($comment);
    }

    public function test_featch_comments_on_post()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);
        $this->assertInstanceOf(Post::class, $comment->post);
    }
    public function test_featch_reactions_on_comment()
    {
        $comment = Comment::factory()->create();
        $reactions = Reaction::factory()->count(3)->create([
            'reactable_id' => $comment->id,
            'reactable_type' => Comment::class
        ]);
        $this->assertCount(3, $comment->reaction);
        $this->assertInstanceOf(Reaction::class, $comment->reaction()->first());
    }

    public function test_comment_can_have_replies()
    {
        $parent_comment = Comment::factory()->create();
        $comments = Comment::factory()->count(10)->create(['parent_id' => $parent_comment->id]);
        $this->assertCount(10, $parent_comment->replies);
        $this->assertInstanceOf(Comment::class, $parent_comment->replies()->first());
    }

    public function test_comment_can_have_parent()
    {
        $parent_comment = Comment::factory()->create();
        $comment = Comment::factory()->create(['parent_id' => $parent_comment->id]);
        $this->assertInstanceOf(Comment::class, $comment->parent);
    }
}
