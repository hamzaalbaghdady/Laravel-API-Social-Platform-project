<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexPostTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_users_can_fetch_the_posts_list(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.index');
        $response = $this->getJson($route);
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [ // * to apply this structure for all items "tasks".
                        "id",
                        "creator_id",
                        "auther",
                        "profile_id",
                        "content",
                        "image",
                        "comments_count",
                        "reactions_count",
                        "created_at",
                        "updated_at"
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
        $route = route('posts.index', [
            'sort' => $field,
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function sortableFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name', 'expected_code']
            ['creator_id', 400],
            ['auther', 400],
            ['profile_id', 400],
            ['content', 200],
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
        $route = route('posts.index', [
            "filter[{$field}]" => $value
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function filterFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name','value','expected_code']
            ['user_id', 1, 400],
        ];
    }

    public function test_unauthenticated_users_can_not_fetch_the_posts_list(): void
    {
        $route = route('posts.index');
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }

}
