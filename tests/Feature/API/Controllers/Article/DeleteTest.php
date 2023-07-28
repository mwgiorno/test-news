<?php

namespace Tests\Feature\API\Controllers\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_an_article_is_soft_deleted(): void
    {
        $article = Article::factory()
            ->for($this->user, 'author')
            ->create();

        $response = $this->actingAs($this->user)
                        ->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200);
        
        $this->assertSoftDeleted($article);
    }

    public function test_only_author_can_delete(): void
    {
        $author = User::factory()->create();

        $article = Article::factory()
            ->for($author, 'author')
            ->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/articles/{$article->id}");
        
        $response
            ->assertStatus(403);
    }
}
