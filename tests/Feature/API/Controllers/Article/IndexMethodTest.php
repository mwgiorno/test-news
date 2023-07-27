<?php

namespace Tests\Feature\API\Controllers\Article;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexMethodTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_get_all_published_paginated_articles(): void
    {
        $articles = Article::factory()
                            ->count(29)
                            ->published()
                            ->create();
        $articles = $articles->chunk(15);

        $resource = ArticleResource::collection($articles[0]);

        $response = $this->getJson('/api/articles');
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->response()->getData(true));

        $resource = ArticleResource::collection($articles[1]);

        $response = $this->getJson('/api/articles?page=2');
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->response()->getData(true));
    }

    public function test_filter_articles_by_section(): void
    {
        $sections = Section::factory()
            ->count(2)
            ->create();

        $articles = Article::factory()
                            ->for($sections[0])
                            ->count(9)
                            ->published()
                            ->create();
        
        Article::factory()
            ->for($sections[1])
            ->count(30)
            ->published()
            ->create();

        $resource = ArticleResource::collection($articles);

        $response = $this->getJson(
            '/api/articles?section=' . $sections[0]->id
        );
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->response()->getData(true));
    }
}
