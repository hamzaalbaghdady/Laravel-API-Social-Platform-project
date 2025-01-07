<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $profile = Profile::factory()->create();
        return [
            'creator_id' => $profile->owner->id,
            'profile_id' => $profile->id,
            'content' => fake()->text(),
            'image' => fake()->image,

        ];
    }
}
