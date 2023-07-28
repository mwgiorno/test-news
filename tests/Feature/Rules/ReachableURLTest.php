<?php

namespace Tests\Feature\Rules;

use App\Rules\ReachableURL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ReachableURLTest extends TestCase
{
    /**
     * @var ReachableURLRule
     */
    protected $rules;

    public function setUp(): void
    {
        parent::setUp();

        $this->rules = ['url' => [new ReachableURL()]];
    }

    public function test_reachable_url_passes(): void
    {
        $url = fake()->imageUrl(640, 480);

        $input = ['url' => $url];

        $this->assertTrue(
            Validator::make($input, $this->rules)->passes()
        );
    }

    public function test_non_reachable_url_fails(): void
    {
        $url = fake()->url();

        $input = ['url' => $url];

        $this->assertTrue(
            Validator::make($input, $this->rules)->fails()
        );
    }
}
