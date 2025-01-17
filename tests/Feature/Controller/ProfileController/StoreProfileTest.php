<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProfileTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_user_can_create_profile(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('profiles.store');
        $response = $this->postJson($route, [
            'user_name' => 'Myname324',
            'about' => 'This text is about me',
            'phone_number' => '13287851581',
            'profile_image' => 'Image.png',
        ]);
        $response->assertCreated();
        $this->assertDatabaseHas('profiles', [
            'user_name' => 'Myname324',
            'user_id' => $user->id,
        ]);
    }

    public function test_unauthenticated_user_can_not_create_profile(): void
    {
        $user = User::factory()->create();
        $route = Route('profiles.store');
        $response = $this->postJson($route, [
            'user_name' => 'Myname324',
            'about' => 'This text is about me',
            'phone_number' => '13287851581',
            'profile_image' => 'Image.png',
        ]);
        $response->assertUnauthorized();
    }

    public function test_username_is_requierd(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $route = Route('profiles.store');
        $response = $this->postJson($route, []);
        $response->assertJsonValidationErrors([
            'user_name' => 'required',
        ]);
    }
    public function test_username_is_unique(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $profile = Profile::factory()->create();
        $route = Route('profiles.store');
        $response = $this->postJson($route, [
            'user_name' => $profile->user_name,
        ]);
        $response->assertJsonValidationErrors(['user_name']);
    }
}
