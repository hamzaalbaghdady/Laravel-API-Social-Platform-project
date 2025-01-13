<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            'user_id' => $user->id,
            'user_name' => fake()->userName,
            'about' => fake()->text,
            'phone_number' => fake()->phoneNumber,
            'profile_image' => fake()->imageUrl,
            'location' => fake()->address,
            'education' => fake()->jobTitle(),
        ];
    }
}
