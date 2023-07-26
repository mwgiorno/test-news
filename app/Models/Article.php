<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class Article extends Model
{
    use Filterable, HasFactory;

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
        'thumbnail'
    ];

    public static function new(
        string $slug,
        int $author,
        int $section,
        string $headline,
        string $body,
        string $thumbnail
    ) 
    {
        return static::create([
            'slug' => $slug,
            'author_id' => $author,
            'section_id' => $section,
            'headline' => $headline,
            'body' => $body,
            'thumbnail' => $thumbnail
        ]);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
