<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProfileTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_update_his_profile(): void
    {

        $profile = Profile::factory()->create();
        Sanctum::actingAs($profile->owner);
        $route = Route('profiles.update', $profile);
        $response = $this->putJson($route, [
            'user_name' => 'updatetest',
        ]);
        $response->assertOk();
        $this->assertEquals('updatetest', $profile->refresh()->user_name);
    }

    public function test_unauthenticated_user_can_not_update_profile(): void
    {
        $profile = Profile::factory()->create();
        $route = Route('profiles.update', $profile);
        $response = $this->putJson($route, [
            'user_name' => 'updatetest',
        ]);
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_not_update_others_profile(): void
    {
        $profile = Profile::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('profiles.update', $profile);
        $response = $this->putJson($route, [
            'user_name' => 'updatetest',
        ]);
        $response->assertForbidden();
    }

}
