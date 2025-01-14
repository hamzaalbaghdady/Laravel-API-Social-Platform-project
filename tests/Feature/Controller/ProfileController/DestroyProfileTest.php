<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyProfileTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_delete_his_profile(): void
    {

        $profile = Profile::factory()->create();
        Sanctum::actingAs($profile->owner);

        $route = Route('profiles.destroy', $profile);
        $response = $this->deleteJson($route);
        $response->assertOk();
        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
            'user_id' => $profile->user_id,
            'user_name' => $profile->user_name,
        ]);
    }

    public function test_unauthenticated_user_can_not_delete_profile(): void
    {
        $profile = Profile::factory()->create();
        $route = Route('profiles.destroy', $profile);
        $response = $this->deleteJson($route);
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_not_delete_others_profile(): void
    {
        $profile = Profile::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('profiles.destroy', $profile);
        $response = $this->deleteJson($route);
        $response->assertForbidden();
    }

}
