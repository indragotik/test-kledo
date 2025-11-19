<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UserService
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * Return users. If perPage supplied, return paginated array with items and meta.
     *
     * @param int|null $perPage
     * @return array
     */
    public function all(?int $perPage = null, array $options = []): array
    {
        $result = $this->users->allPaginated($perPage, $options);
        if ($perPage) {
            // LengthAwarePaginator
            $items = array_map(fn(User $u) => UserDTO::fromModel($u)->toArray(), $result->items());
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

        return array_map(fn(User $u) => UserDTO::fromModel($u)->toArray(), $result->all());
    }

    public function find(int $id): ?UserDTO
    {
        $user = $this->users->find($id);
        return $user ? UserDTO::fromModel($user) : null;
    }

    public function store(array $data): UserDTO
    {
        $user = $this->users->create($data);
        return UserDTO::fromModel($user);
    }

    public function update(int $id, array $data): ?UserDTO
    {
        $user = $this->users->find($id);
        if (! $user) {
            return null;
        }
        $this->users->update($user, $data);
        return UserDTO::fromModel($user->refresh());
    }

    public function delete(int $id): bool
    {
        $user = $this->users->find($id);
        if (! $user) {
            return false;
        }
        return $this->users->delete($user);
    }
}
