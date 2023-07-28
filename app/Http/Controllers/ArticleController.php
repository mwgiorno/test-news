<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\CreateRequest;
use App\Http\Requests\Article\UpdateRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::published()
            ->filter($request->only('section', 'headline'))
            ->paginateFilter();

        return ArticleResource::collection($articles);
    }

    public function create(CreateRequest $request)
    {
        $article = Article::new(
            $request->slug,
            $request->user()->id,
            $request->section,
            $request->headline,
            $request->body,
            $request->thumbnail
        );

        $article->load('section', 'author');

        return new ArticleResource($article);
    }

    public function get(Article $article)
    {
        return new ArticleResource($article);
    }

    public function update(Article $article, UpdateRequest $request)
    {
        $request->validate([
            'slug' => Rule::unique('articles', 'slug')
                        ->ignore($article->slug)
        ]);

        $article->fill([
            'slug' => $request->slug,
            'section_id' => $request->section,
            'headline' => $request->headline,
            'body' => $request->body,
            'thumbnail' => $request->thumbnail
        ]);

        $article->save();

        return new ArticleResource($article->fresh());
    }

    public function destroy(Article $article)
    {
        $article->delete();
    }
}
