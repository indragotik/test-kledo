<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\DTO\ProductDTO;
use App\Models\Product;

final class ProductService
{
    public function __construct(private ProductRepository $repo)
    {
    }

    /**
     * Return products. If perPage supplied, return paginated array with items and meta.
     *
     * @param int|null $perPage
     * @return array
     */
    public function all(?int $perPage = null, array $options = []): array
    {
        $result = $this->repo->allPaginated($perPage, $options);
        if ($perPage) {
            $items = array_map(fn(Product $p) => ProductDTO::fromModel($p)->toArray(), $result->items());
            return [
                'items' => $items,
                'pagination' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage(),
                ],
            ];
        }

        return array_map(fn(Product $p) => ProductDTO::fromModel($p)->toArray(), $result->all());
    }

    public function find(int $id): ?ProductDTO
    {
        $p = $this->repo->find($id);
        return $p ? ProductDTO::fromModel($p) : null;
    }

    public function store(array $data): ProductDTO
    {
        $p = $this->repo->create($data);
        return ProductDTO::fromModel($p);
    }

    public function update(int $id, array $data): ?ProductDTO
    {
        $p = $this->repo->find($id);
        if (! $p) {
            return null;
        }
        $this->repo->update($p, $data);
        return ProductDTO::fromModel($p->refresh());
    }

    public function delete(int $id): bool
    {
        $p = $this->repo->find($id);
        if (! $p) {
            return false;
        }
        return $this->repo->delete($p);
    }
}
