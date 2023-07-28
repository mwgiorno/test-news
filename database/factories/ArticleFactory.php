<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->slug(),
            'author_id' => User::factory(),
            'section_id' => Section::factory(),
            'headline' => fake()->sentence(),
            'body' => fake()->text(500),
            'thumbnail' => fake()->imageUrl(1920, 1080, 'landscapes', true, null, false, 'jpg'),
            'published' => false
        ];
    }

    /**
     * Indicate that the article is published.
     */
    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
            ];
        });
    }
}
