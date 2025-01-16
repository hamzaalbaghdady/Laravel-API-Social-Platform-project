<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class UpdateUserTest extends TestCase
{
    /**
     * A basic test for user update.
     */
    public function test_authenticated_user_can_update_his_information(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('users.update', $user);
        $response = $this->putJson($route, [
            'first_name' => 'MynewName',
            'email' => 'newname@gmail.com',
            'old_password' => 'password',
        ]);
        $response->assertOk();
        $this->assertEquals('newname@gmail.com', $user->refresh()->email);
    }
    public function test_authenticated_user_can_not_update_his_information_without_password(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('users.update', $user);
        $response = $this->putJson($route, [
            'first_name' => 'MynewName',
            'email' => 'newname@gmail.com',
            'old_password' => 'false_password',
        ]);
        $response->assertForbidden();
    }
    public function test_authenticated_user_can_not_update_others(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $route = Route('users.update', $user2);
        $response = $this->putJson($route, [
            'first_name' => 'MynewName',
            'email' => 'newname@gmail.com',
            'old_password' => 'password',
        ]);
        $response->assertForbidden();
    }
    public function test_unauthenticated_user_can_not_update(): void
    {
        $user = User::factory()->create();
        $route = Route('users.update', $user);
        $response = $this->putJson($route, [
            'first_name' => 'MynewName',
            'email' => 'newname@gmail.com',
            'old_password' => 'password',
        ]);
        $response->assertUnauthorized();
    }



}
