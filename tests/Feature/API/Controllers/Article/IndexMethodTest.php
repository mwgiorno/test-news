<?php

namespace Tests\Feature\API\Controllers\Article;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexMethodTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_getting_all_published_paginated_articles(): void
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

    public function test_filtering_articles_by_section(): void
    {
        $sections = Section::factory()
            ->count(2)
            ->create();

        $articles = Article::factory()
                            ->for($sections[0])
                            ->count(25)
                            ->published()
                            ->create()
                            ->chunk(15);
        
        Article::factory()
            ->for($sections[1])
            ->count(30)
            ->published()
            ->create();

        $resource = ArticleResource::collection($articles[0]);

        $response = $this->getJson(
            '/api/articles?' . 
            Arr::query(['section' => $sections[0]->id])
        );
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->response()->getData(true));

        $resource = ArticleResource::collection($articles[1]);

        $response = $this->getJson(
            '/api/articles?' .
            Arr::query(
                [
                    'section' => $sections[0]->id,
                    'page' => 2
                ]
            )
        );
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->response()->getData(true));
    }

    public function test_searching_for_one_article_by_headline(): void
    {
        $articles = Article::factory()
            ->count(100)
            ->published()
            ->create();

        $article = $articles[rand(0, ($articles->count() - 1))];

        $resource = ArticleResource::collection([$article]);

        $response = $this->getJson(
            '/api/articles?headline=' . $article->headline
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->response()->getData(true));
    }
}
