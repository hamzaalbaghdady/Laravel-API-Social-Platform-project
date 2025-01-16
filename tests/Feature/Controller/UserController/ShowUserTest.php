<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class ShowUserTest extends TestCase
{
    /**
     * A basic test for user soft view.
     */
    public function test_authenticated_user_can_view(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = Route('users.show', $user);
        $response = $this->getJson($route);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'date_of_birth',
                "profile_id",
            ]
        ]);
    }

    public function test_authenticated_user_can_not_view_others(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $route = Route('users.show', $user2);
        $response = $this->getJson($route);
        $response->assertForbidden();
    }
    public function test_unauthenticated_user_can_not_view(): void
    {
        $user = User::factory()->create();
        $route = Route('users.show', $user);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }



}
