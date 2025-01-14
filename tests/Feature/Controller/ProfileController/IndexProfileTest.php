<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexProfileTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_users_can_fetch_the_profiles_list(): void
    {
        $profiel = Profile::factory()->create();
        Sanctum::actingAs($profiel->owner);

        $route = route('profiles.index');
        $response = $this->getJson($route);
        // $response->dd(); // to see the result while developing
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [ // * to apply this structure for all items "tasks".
                        'id',
                        'user_id',
                        'user_name',
                        'about',
                        'phone_number',
                        'profile_image',
                        'location',
                        'education',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    /**
     * Summary of test_sortable_fields
     * @dataProvider sortableFields
     */
    public function test_sortable_fields($field, $expected_code)
    {
        Sanctum::actingAs(User::factory()->create());
        $route = route('profiles.index', [
            'sort' => $field,
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function sortableFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name', 'expected_code']
            ['id', 400],
            ['user_id', 400],
            ['user_name', 200],
            ['phone_number', 400],
            ['location', 200],
            ['education', 400],
            ['created_at', 200],
            ['updated_at', 200],
        ];
    }
    /**
     * Summary of test_filterable_fields
     * @dataProvider filterFields
     */
    public function test_filterable_fields($field, $value, $expected_code)
    {
        Sanctum::actingAs(User::factory()->create());
        $route = route('profiles.index', [
            "filter[{$field}]" => $value
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function filterFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name','value','expected_code']
            ['id', 1, 400],
            ['user_name', 'foo', 400],
            ['location', 'gaza', 200],
        ];
    }

    public function test_unauthenticated_users_can_not_fetch_the_profiles_list(): void
    {
        $route = route('profiles.index');
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }

}
