<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexCommentTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_authenticated_users_can_fetch_the_comment_list(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = route('posts.comments.index', $post);
        $response = $this->getJson($route);

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    'comments' => [ // * to apply this structure for all items "tasks".
                        '*' => [
                            "id",
                            "content",
                            "creator_id",
                            "post_id",
                            "parent_id",
                            "reactions_count",
                            "created_at",
                            "updated_at"
                        ]
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
        $post = Post::factory()->create();

        Sanctum::actingAs(User::factory()->create());
        $route = route('posts.comments.index', [
            $post,
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
            ['id', 400],
            ['post_id', 400],
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
        $post = Post::factory()->create();
        Sanctum::actingAs(User::factory()->create());
        $route = route('posts.comments.index', [
            $post,
            "filter[{$field}]" => $value
        ]);
        $response = $this->getJson($route);
        $response->assertStatus($expected_code);
    }
    public function filterFields()
    {
        return [  // this is like a test cases, each one will be tested seperatly
            // ['field_name','value','expected_code']
            ['parent_id', 1, 200],
        ];
    }

    public function test_unauthenticated_users_can_not_fetch_the_posts_list(): void
    {
        $post = Post::factory()->create();
        $route = route('posts.comments.index', $post);
        $response = $this->getJson($route);
        $response->assertUnauthorized();
    }

}
