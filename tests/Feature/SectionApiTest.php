<?php

namespace Tests\Feature;

use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SectionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_making_a_get_all_request(): void
    {
        $resourse = SectionResource::collection(Section::all());

        $response = $this->getJson(
            '/api/sections'
        );
 
        $response
            ->assertStatus(200)
            ->assertExactJson($resourse->response()->getData(true));
    }

    public function test_making_a_create_request(): void
    {
        $slug = fake()->slug();
        $name = fake()->word();

        $response = $this->postJson(
            '/api/sections',
            ['slug' => $slug, 'name' => $name]
        );
 
        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'slug' => $slug,
                'name' => $name
            ]);
    }

    public function test_making_a_get_request(): void
    {
        $section = Section::factory()->create();

        $response = $this->getJson(
            '/api/sections/' . $section->slug
        );
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment($section->toArray());
    }

    public function test_making_an_update_request(): void
    {
        $section = Section::factory()->create();

        $newSlug = fake()->slug();
        $newName = fake()->word();

        $response = $this->patchJson(
            '/api/sections/' . $section->slug,
            ['slug' => $newSlug, 'name' => $newName]
        );
 
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'slug' => $newSlug,
                'name' => $newName
            ]);
    }

    public function test_making_an_update_request_with_non_unique_slug(): void
    {
        $sections = Section::factory(2)->create();

        $newName = fake()->word();

        $response = $this->patchJson(
            '/api/sections/' . $sections[0]->slug,
            ['slug' => $sections[1]->slug, 'name' => $newName]
        );
 
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor('slug');
    }
}
