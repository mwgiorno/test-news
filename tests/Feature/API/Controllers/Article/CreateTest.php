<?php

namespace Tests\Feature\API\Controllers\Article;

use App\Models\Article;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_creating_article(): void
    {
        $section = Section::factory()->create();
        $article = Article::factory()->make();

        $response = $this->actingAs($this->user)
                        ->postJson(
                            '/api/articles',
                            [
                                'slug' => $article->slug,
                                'section' => $section->id,
                                'headline' => $article->headline,
                                'body' => $article->body,
                                'thumbnail' => $article->thumbnail
                            ]
                        );
 
        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'slug' => $article->slug,
                'section' => [
                    'id' => $section->id,
                    'slug' => $section->slug,
                    'name' => $section->name,
                ],
                'author' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ],
                'headline' => $article->headline,
                'body' => $article->body,
                'thumbnail' => $article->thumbnail,
                'published' => false
            ]);
    }

    public function test_creating_with_invalid_image_url_fails(): void
    {
        $section = Section::factory()->create();
        $article = Article::factory()->make();
        $url = fake()->url();

        $response = $this->actingAs($this->user)
                        ->postJson(
                            '/api/articles',
                            [
                                'slug' => $article->slug,
                                'section' => $section->id,
                                'headline' => $article->headline,
                                'body' => $article->body,
                                'thumbnail' => $url
                            ]
                        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
