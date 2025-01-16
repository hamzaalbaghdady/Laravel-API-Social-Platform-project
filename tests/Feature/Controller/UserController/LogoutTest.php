<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    /**
     * A basic test for user logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('logout');
        $response = $this->postJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id
        ]);
    }
    public function test_unauthenticated_user_can_not_logout(): void
    {
        $user = User::factory()->create();
        $route = Route('logout');
        $response = $this->postJson($route);
        $response->assertUnauthorized();
    }



}
