<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\ProductService;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Products")
 */
final class ProductController extends Controller
{
    private ProductService $products;

    public function __construct(ProductService $products)
    {
        $this->products = $products;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get product list",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer"), description="Items per page", example=10),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string"), description="Search term"),
     *     @OA\Parameter(name="sort_by", in="query", @OA\Schema(type="string"), description="Field to sort by", example="name"),
     *     @OA\Parameter(name="sort_dir", in="query", @OA\Schema(type="string"), description="Sort direction: asc or desc", example="desc"),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseProducts")
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

        $data = $this->products->all($perPage, array_filter($options, fn($v) => $v !== null && $v !== ''));
        return ApiResponse::success($data, 'Success');
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Detail product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Detail product successful",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseProduct")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->products->find($id);
        if (! $product) {
            return ApiResponse::notFound('Product Not found');
        }

        return ApiResponse::success($product->toArray(), 'Detail product successful');
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create product",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Create product successful",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseProduct")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        $dto = $this->products->store($request->validated());
        return ApiResponse::success($dto->toArray(), 'Create product successful', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductCreate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update product successful",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponseProduct")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $dto = $this->products->update($id, $request->validated());
        if (! $dto) {
            return ApiResponse::notFound('Product Not found');
        }
        return ApiResponse::success($dto->toArray(), 'Update product successful');
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Delete product success",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $product = $this->products->find($id);
        if (! $product) {
            return ApiResponse::notFound('Product Not found');
        }
        $this->products->delete($id);
        return ApiResponse::success(null, 'Delete product success');
    }
}
