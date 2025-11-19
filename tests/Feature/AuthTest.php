<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;

final class AuthTest extends TestCase
{
    // use RefreshDatabase;

    public function test_register_and_login(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['data', 'token']);

        $login = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $login->assertStatus(200)->assertJsonStructure(['data', 'token']);
    }
}
