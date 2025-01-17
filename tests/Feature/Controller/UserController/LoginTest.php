<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic test for user login.
     */
    public function test_required_inputs(): void
    {
        $route = Route('login');
        $response = $this->postJson($route, []);
        $response->assertJsonValidationErrors(
            [
                'email',
                'password',
            ]
        );
    }


    public function test_user_can_login_with_correct_password_and_email(): void
    {
        $user = User::factory()->create();
        $route = Route('login');
        $response = $this->postJson($route, [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertOk();
        $this->assertDatabaseHas(
            'personal_access_tokens',
            [
                'tokenable_id' => $user->id,
            ]
        );
    }

    public function test_user_can_not_login_with_uncorrect_password(): void
    {
        $user = User::factory()->create();
        $route = Route('login');
        $response = $this->postJson($route, [
            'email' => $user->email,
            'password' => 'false_password',
        ]);
        $response->assertUnauthorized();
    }
}
