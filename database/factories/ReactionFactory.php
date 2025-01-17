<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $reactable_type = $this->faker->randomElement([
            Post::class,
            Comment::class,
        ]);
        $reactable = $reactable_type::factory()->create();

        return [
            'creator_id' => $user->id,
            'type' => $this->faker->randomElement(['like', 'love', 'haha', 'wow', 'care', 'sad', 'angry']),
            'reactable_id' => $reactable->id,
            'reactable_type' => get_class($reactable),
        ];
    }
}
