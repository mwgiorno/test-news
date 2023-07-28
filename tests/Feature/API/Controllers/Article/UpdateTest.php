<?php

namespace Tests\Feature\API\Controllers\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_update_is_successful(): void
    {
        $article = Article::factory()
            ->for($this->user, 'author')
            ->create();

        $newArticle = Article::factory()->make();

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
            ->assertStatus(200);
    }

    public function test_only_author_can_update(): void
    {
        $author = User::factory()->create();

        $article = Article::factory()
            ->for($author, 'author')
            ->create();

        $newArticle = Article::factory()->make();

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
            ->assertStatus(403);
    }
}
