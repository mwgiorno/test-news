<?php

namespace Tests\Feature\API\Controllers\Article;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTest extends TestCase
{
    use RefreshDatabase;

    public function test_retrieve_an_article(): void
    {
        $article = Article::factory()->create();
        $resource = new ArticleResource($article);

        $response = $this->getJson('/api/articles/' . $article->id);

        $response
            ->assertStatus(200)
            ->assertExactJson(
                $resource->response()
                        ->getData(true)
            );
    }
}
