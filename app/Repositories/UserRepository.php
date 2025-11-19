<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class UserRepository
{
    public function all(): Collection
    {
        return User::query()->get();
    }

    /**
     * Return paginated users when perPage provided, otherwise collection.
     *
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection
     */
    public function allPaginated(?int $perPage = null, array $options = [])
    {
        $query = User::query();

        // search
        $search = $options['search'] ?? null;
        if ($search) {
            $fields = ['name', 'email'];
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

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
