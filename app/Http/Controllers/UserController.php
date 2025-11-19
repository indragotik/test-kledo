<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;

/**
 * @OA\Tag(name="Users")
 */
final class UserController extends Controller
{
    private UserService $users;

    public function __construct(UserService $users)
    {
        $this->users = $users;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get user list",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer"), description="Items per page", example=10),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string"), description="Search term"),
     *     @OA\Parameter(name="sort_by", in="query", @OA\Schema(type="string"), description="Field to sort by", example="name"),
     *     @OA\Parameter(name="sort_dir", in="query", @OA\Schema(type="string"), description="Sort direction: asc or desc", example="desc"),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseUsers")
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $perPage = (int) request()->query('per_page', 10);
        $options = [
            'search' => request()->query('search'),
            'sort_by' => request()->query('sort_by'),
            'sort_dir' => request()->query('sort_dir'),
        ];

        $data = $this->users->all($perPage, array_filter($options, fn($v) => $v !== null && $v !== ''));
        return ApiResponse::success($data, 'Success');
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Detail user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Detail user successful",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseUser")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->users->find($id);
        if (! $user) {
            return ApiResponse::notFound('User Not found');
        }

        return ApiResponse::success($user->toArray(), 'Detail user successful');
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create user",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Create user successful",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseUser")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $dto = $this->users->store($request->validated());
        return ApiResponse::success($dto->toArray(), 'Create user successful', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update user successful",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseUser")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $dto = $this->users->update($id, $request->validated());
        if (! $dto) {
            return ApiResponse::notFound('User Not found');
        }
        return ApiResponse::success($dto->toArray(), 'Update user successful');
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Delete user success",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->users->find($id);
        if (! $user) {
            return ApiResponse::notFound('User Not found');
        }
        $this->users->delete($id);
        return ApiResponse::success(null, 'Delete user success');
    }
}
