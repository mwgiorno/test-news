<?php

namespace Tests\Feature\Tokens;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RevokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_revoke_all_access_tokens(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->createToken('auth_token');
        $user->createToken('auth_token');

        $response = $this->deleteJson('/api/tokens', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'name' => 'auth_token'
        ]);
    }
}
