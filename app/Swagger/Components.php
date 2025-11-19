<?php

namespace App\Swagger;

/**
 * @OA\Components(
 *   @OA\Schema(
 *     schema="ApiResponse",
 *     @OA\Property(property="status", type="boolean"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="data", type="object")
 *   ),
 *   @OA\Schema(
 *     schema="PaginationMeta",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer"),
 *     @OA\Property(property="per_page", type="integer"),
 *     @OA\Property(property="total", type="integer"),
 *     @OA\Property(property="last_page", type="integer")
 *   ),
 *   @OA\Schema(
 *     schema="PaginatedUsers",
 *     type="object",
 *     @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/User")),
 *     @OA\Property(property="pagination", ref="#/components/schemas/PaginationMeta")
 *   ),
 *   @OA\Schema(
 *     schema="PaginatedProducts",
 *     type="object",
 *     @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/Product")),
 *     @OA\Property(property="pagination", ref="#/components/schemas/PaginationMeta")
 *   ),
 *   @OA\Schema(
 *     schema="ApiResponseUsers",
 *     type="object",
 *     @OA\Property(property="status", type="boolean"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="data", ref="#/components/schemas/PaginatedUsers")
 *   ),
 *   @OA\Schema(
 *     schema="ApiResponseProducts",
 *     type="object",
 *     @OA\Property(property="status", type="boolean"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="data", ref="#/components/schemas/PaginatedProducts")
 *   ),
 *   @OA\Schema(
 *       schema="ApiResponseProduct",
 *       type="object",
 *       @OA\Property(property="status", type="boolean"),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(property="data", ref="#/components/schemas/Product")
 *   ),
 *   @OA\Schema(
 *       schema="ApiResponseUser",
 *       type="object",
 *       @OA\Property(property="status", type="boolean"),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(property="data", ref="#/components/schemas/User")
 *   ),
 *   @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email")
 *   ),
 *   @OA\Schema(
 *     schema="UserCreate",
 *     type="object",
 *     required={"name","email","password","password_confirmation"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string"),
 *     @OA\Property(property="password_confirmation", type="string")
 *   ),
 *   @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="price", type="number", format="decimal"),
 *     @OA\Property(property="stock", type="integer"),
 *     @OA\Property(property="picture_url", type="string", nullable=true)
 *   ),
 *   @OA\Schema(
 *     schema="ProductCreate",
 *     type="object",
 *     required={"name","price","stock"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="price", type="number", format="decimal"),
 *     @OA\Property(property="stock", type="integer"),
 *     @OA\Property(property="picture_url", type="string", nullable=true)
 *   )
 * )
 */
final class Components
{
    // This file only contains OpenAPI annotations used by l5-swagger to generate components
}
