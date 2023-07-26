<?php

namespace Tests\Feature;

use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_can_be_instantiated(): void
    {
        $section = Section::factory()->create();
    
        $this->assertModelExists($section);
    }

    public function test_models_can_be_created_with_new_static_method(): void
    {
        $section = Section::new(fake()->slug(), fake()->word());

        $this->assertModelExists($section);
    }
}
