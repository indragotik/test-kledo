<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

final class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->users->create($data);
    }

    public function login(User $user): string
    {
        // create sanctum token
        return $user->createToken('api')->plainTextToken;
    }

    public function logout(User $user): void
    {
        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }
    }

    public function me(User $user): User
    {
        return $user;
    }
}
