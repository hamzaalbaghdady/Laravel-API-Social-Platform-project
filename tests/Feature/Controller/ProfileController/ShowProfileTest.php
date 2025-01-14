<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProfileTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_view_his_profile(): void
    {

        $profile = Profile::factory()->create();
        Sanctum::actingAs($profile->owner);
        $route = Route('profiles.show', $profile);
        $response = $this->getJson($route);
        $response->assertOk();
    }

    public function test_unauthenticated_user_can_not_view_profile(): void
    {
        $profile = Profile::factory()->create();
        $route = Route('profiles.show', $profile);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }

}
