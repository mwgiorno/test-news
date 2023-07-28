<?php

namespace Tests\Feature\Rules;

use App\Rules\ValidImageURL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidImageURLTest extends TestCase
{
    protected $rules;

    public function setUp(): void
    {
        parent::setUp();

        $this->rules = ['url' => [new ValidImageURL()]];
    }

    public function test_valid_image_url_passes(): void
    {
        $url = fake()
            ->imageUrl(
                ValidImageURL::VALID_WIDTH,
                ValidImageURL::VALID_HEIGHT
            );

        $input = ['url' => $url];

        $this->assertTrue(
            Validator::make($input, $this->rules)->passes()
        );
    }

    public function test_invalid_image_url_fails(): void
    {
        $url = fake()->url();

        $input = ['url' => $url];

        $this->assertTrue(
            Validator::make($input, $this->rules)->fails()
        );
    }

    public function test_image_url_with_invalid_dimensions_fails(): void
    {
        $url = fake()
            ->imageUrl(
                ValidImageURL::VALID_WIDTH / 2,
                ValidImageURL::VALID_HEIGHT / 2
            );

        $input = ['url' => $url];

        $this->assertTrue(
            Validator::make($input, $this->rules)->fails()
        );
    }
}
