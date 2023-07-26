<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name'
    ];

    public static function new(
        string $slug,
        string $name
    ) 
    {
        return static::create([
            'slug' => $slug,
            'name' => $name
        ]);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
