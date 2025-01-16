<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;


class UserTest extends TestCase
{

    public function test_user_can_have_profile()
    {
        $user = User::factory()->create();
        $profile = $user->profile()->create([
            'user_name' => 'testname',
            'about' => 'why is this requierd',
        ]);
        $this->assertModelExists($profile);
        $this->assertInstanceOf(Profile::class, $profile);
    }
    public function test_user_can_have_post()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $post = $user->posts()->make([
            'content' => 'test Post',
        ]);
        $post->profile()->associate($profile);
        $post->save();
        $this->assertModelExists($post);
        $this->assertInstanceOf(Post::class, $post);
    }
    public function test_user_can_have_comment()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create();

        $comment = $user->comments()->make([
            'content' => 'test comment',
        ]);
        $comment->post()->associate($post);
        $comment->save();
        $this->assertModelExists($comment);
        $this->assertInstanceOf(Comment::class, $comment);
    }
    public function test_user_can_have_reaction()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create();

        $reaction = $user->reactions()->make([
            'type' => 'love',
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);
        $reaction->reactable()->associate($post);
        $reaction->save();
        $this->assertModelExists($reaction);
        $this->assertInstanceOf(Reaction::class, $reaction);
    }
    public function test_user_can_have_followers()
    {
        // Arrange: Create users
        $user = User::factory()->create();
        $follower = User::factory()->create();

        // Act: Attach follower to user
        $user->followers()->syncWithoutDetaching($follower);
        $this->assertDatabaseHas('follows', [
            'follower_id' => $follower->id,
            'following_id' => $user->id
        ]);
    }
    public function test_user_can_follow_others()
    {
        // Arrange: Create users
        $user = User::factory()->create();
        $following = User::factory()->create();

        $user->following()->syncWithoutDetaching($following);
        $this->assertDatabaseHas('follows', [
            'follower_id' => $user->id,
            'following_id' => $following->id
        ]);
    }


    public function test_user_can_have_multiple_followers_and_followings()
    {
        // Arrange: Create users
        $user = User::factory()->create();
        $followers = User::factory()->count(3)->create();
        $followings = User::factory()->count(3)->create();

        // Act: Attach multiple followers and followings
        $user->followers()->attach($followers);
        $user->following()->attach($followings);

        // Assert: Check the counts and instances
        $this->assertCount(3, $user->followers);
        $this->assertCount(3, $user->following);
        $this->assertInstanceOf(User::class, $user->followers->first());
        $this->assertInstanceOf(User::class, $user->following->first());
    }

}
