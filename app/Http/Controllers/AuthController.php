<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @OA\Tag(name="Auth")
 */
final class AuthController extends Controller
{
    private AuthService $auth;
    private UserRepository $users;
    public function __construct(AuthService $auth, UserRepository $users)
    {
        $this->auth = $auth;
        $this->users = $users;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resgitered Successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="token", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422, 
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = $this->auth->register($credentials);
        $token = $this->auth->login($user);
    return ApiResponse::successLogin($user, $token, 'Resgitered Successful', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="token", type="string"),
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422, 
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation Error"),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}}),
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = $this->users->all()->firstWhere('email', $credentials['email']);
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return ApiResponse::unauthorized('Invalid credentials');
        }
        $token = $this->auth->login($user);
        return ApiResponse::successLogin($user, $token, 'Resgitered Successful');
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout the currently authenticated user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out"),
     *             @OA\Property(property="data", type="object", example=null),
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        if ($user) {
            $this->auth->logout($user);
        }
        return ApiResponse::success(null, 'Logged out');
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     tags={"Auth"},
     *     summary="Detail a user profile",
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object"),
     *         )
     *     ),
     * )
     */
    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success($request->user(), 'Profile fetched successfully');
    }
}
