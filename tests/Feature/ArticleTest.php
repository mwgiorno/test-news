<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_can_be_instantiated(): void
    {
        $article = Article::factory()->create();

        $this->assertModelExists($article);
    }

    public function test_models_can_be_created_with_new_static_method(): void
    {
        $article = Article::new(
            fake()->slug(),
            User::factory()->create()->id,
            Section::factory()->create()->id,
            fake()->word(),
            fake()->text(500),
            fake()->imageUrl(1920, 1080, 'landscapes', true, null, false, 'jpg')
        );

        $this->assertModelExists($article);
    }

    public function test_soft_deletes(): void
    {
        $article = Article::factory()->create();

        $article->delete();

        $this->assertSoftDeleted($article);
    }
}
