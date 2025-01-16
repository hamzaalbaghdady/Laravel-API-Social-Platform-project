<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class UserSoftDeleteTest extends TestCase
{
    /**
     * A basic test for user soft delete.
     */
    public function test_authenticated_user_can_delete(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = Route('users.destroy', $user);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertNotEquals($user->refresh()->deleted_at, null);
    }

    public function test_authenticated_user_can_not_delete_others(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $route = Route('users.destroy', $user2);
        $response = $this->deleteJson($route);
        $response->assertForbidden();
    }
    public function test_unauthenticated_user_can_not_delete(): void
    {
        $user = User::factory()->create();
        $route = Route('users.destroy', $user);
        $response = $this->deleteJson($route);
        $response->assertUnauthorized();
    }



}
