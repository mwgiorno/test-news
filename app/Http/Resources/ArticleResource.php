<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section' => [
                'id' => $this->section->id,
                'slug' => $this->section->slug,
                'name' => $this->section->name,
            ],
            'author' => [
                'name' => $this->author->name,
                'email' => $this->author->email,
            ],
            'slug' => $this->slug,
            'headline' => $this->headline,
            'body' => $this->body,
            'thumbnail' => $this->thumbnail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published' => $this->published 
        ];
    }
}
