<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final class ProductRepository
{
    public function all(): Collection
    {
        return Product::query()->get();
    }

    /**
     * Return paginated products when perPage provided, otherwise collection.
     *
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection
     */
    public function allPaginated(?int $perPage = null, array $options = [])
    {
        $query = Product::query();

        // search
        $search = $options['search'] ?? null;
        if ($search) {
            $fields = ['name', 'description'];
            $query->where(function ($q) use ($fields, $search) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        // sorting
        $sortBy = $options['sort_by'] ?? 'id';
        $sortDir = strtolower($options['sort_dir'] ?? 'desc');
        if (! in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }
        $query->orderBy($sortBy, $sortDir);

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->fill($data);
        $product->save();
        return $product;
    }

    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }
}
