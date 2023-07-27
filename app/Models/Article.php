<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use Filterable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'author_id',
        'section_id',
        'headline',
        'body',
        'thumbnail',
        'published'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'section:id,slug,name',
        'author:id,name,email'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published' => 'boolean',
    ];

    public static function new(
        string $slug,
        int $author,
        int $section,
        string $headline,
        string $body,
        string $thumbnail,
        bool $published = false
    ) 
    {
        return static::create([
            'slug' => $slug,
            'author_id' => $author,
            'section_id' => $section,
            'headline' => $headline,
            'body' => $body,
            'thumbnail' => $thumbnail,
            'published' => $published
        ]);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }
}
