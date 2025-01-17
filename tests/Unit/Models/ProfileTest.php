<?php

namespace Tests\Unit\Models;

use App\Models\Profile;
use Tests\TestCase;
use App\Models\User;


class ProfileTest extends TestCase
{

    public function test_user_can_have_profile()
    {

        $user = User::factory()->create();
        $profile = $user->profile()->make([
            'user_name' => 'testname',
        ]);
        $profile->owner()->associate($profile->owner);
        $profile->save();
        $this->assertModelExists($profile);

    }
    public function test_profile_can_have_posts()
    {

        $profile = Profile::factory()->create();
        $post = $profile->posts()->make([
            'content' => 'My New Post',
        ]);
        $post->creator()->associate($profile->user_id);
        $post->save();
        $this->assertModelExists($post);

    }
}
