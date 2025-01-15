<?php

namespace Tests\Unit\Models;

use App\Models\Reaction;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;


class ReactionTest extends TestCase
{

    public function test_reactions_belongs_to_user()
    {
        $reaction = Reaction::factory()->create();
        $user = $reaction->creator;
        $this->assertModelExists($user);
    }
    public function test_reactions_belongs_to_model() // model is either Post or Comment
    {
        $reaction = Reaction::factory()->create();
        $model = $reaction->reactable;
        $this->assertModelExists($model);
    }


}
