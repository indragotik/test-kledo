<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Models\User;

final class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user(): void
    {
        $repo = new UserRepository();
        $service = new AuthService($repo);

        $user = $service->register(['name' => 'A', 'email' => 'a@example.com', 'password' => 'password']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('a@example.com', $user->email);
    }
}
