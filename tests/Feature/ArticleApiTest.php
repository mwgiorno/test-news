<?php

namespace Tests\Feature;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_making_a_get_all_request(): void
    {
        $articles = Article::factory()
                            ->count(4)
                            ->published()
                            ->create();
        $articles = ArticleResource::collection($articles);

        $response = $this->getJson('/api/articles');
 
        $response
            ->assertStatus(200)
            ->assertExactJson($articles->response()->getData(true));
    }

    public function test_making_a_create_request(): void
    {
        $slug = fake()->slug();
        $section = Section::factory()->create();
        $headline = fake()->sentence();
        $body = fake()->text(500);
        $thumbnail = fake()->imageUrl(
                1920, 1080, 'landscapes', true, null, false, 'jpg'
            );

        $response = $this->actingAs($this->user)
                        ->postJson(
                            '/api/articles',
                            [
                                'slug' => $slug,
                                'section' => $section->id,
                                'headline' => $headline,
                                'body' => $body,
                                'thumbnail' => $thumbnail
                            ]
                        );
 
        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'slug' => $slug,
                'section' => [
                    'id' => $section->id,
                    'slug' => $section->slug,
                    'name' => $section->name,
                ],
                'author' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ],
                'headline' => $headline,
                'body' => $body,
                'thumbnail' => $thumbnail,
                'published' => false
            ]);
    }

    public function test_making_a_get_request(): void
    {
        $article = Article::factory()->create();
        $resource = new ArticleResource($article);

        $response = $this->actingAs($this->user)
                        ->getJson('/api/articles/' . $article->id);

        $response
            ->assertStatus(200)
            ->assertExactJson(
                $resource->response()
                        ->getData(true)
            );
    }

    public function test_making_an_update_request(): void
    {
        $article = Article::factory()
            ->for($this->user, 'author')
            ->create();

        $newArticle = Article::factory()
            ->for($this->user, 'author')
            ->make();

        $response = $this->actingAs($this->user)
            ->patchJson(
                "/api/articles/{$article->id}",
                [
                    'slug' => $newArticle->slug,
                    'section' => $newArticle->section->id,
                    'headline' => $newArticle->headline,
                    'body' => $newArticle->body,
                    'thumbnail' => $newArticle->thumbnail
                ]
            );
        
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'slug' => $newArticle->slug,
                'section' => [
                    'id' => $newArticle->section->id,
                    'slug' => $newArticle->section->slug,
                    'name' => $newArticle->section->name,
                ],
                'author' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ],
                'headline' => $newArticle->headline,
                'body' => $newArticle->body,
                'thumbnail' => $newArticle->thumbnail
            ]);
    }

    public function test_making_a_delete_request(): void
    {
        $article = Article::factory()->create();

        $response = $this->actingAs($this->user)
                        ->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200);
        
        $this->assertSoftDeleted($article);
    }
}
