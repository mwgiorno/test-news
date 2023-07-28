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
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')
                    ->ignore($article->slug)
            ]
        ]);

        $article->fill([
            'slug' => $request->input('slug', $article->slug),
            'section_id' => $request->input('section', $article->section_id),
            'headline' => $request->input('headline', $article->headline),
            'body' => $request->input('body', $article->body),
            'thumbnail' => $request->input('thumbnail', $article->thumbnail)
        ]);

        $article->save();
    }

    public function destroy(Article $article)
    {
        $article->delete();
    }
}
