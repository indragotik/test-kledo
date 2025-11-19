<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ProductService;
use App\Repositories\ProductRepository;

final class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_and_find_product(): void
    {
        $repo = new ProductRepository();
        $service = new ProductService($repo);

        $dto = $service->store(['name' => 'X', 'description' => 'D', 'price' => 1.23, 'stock' => 5]);

        $this->assertSame('X', $dto->name);
        $found = $service->find($dto->id);
        $this->assertNotNull($found);
    }
}
