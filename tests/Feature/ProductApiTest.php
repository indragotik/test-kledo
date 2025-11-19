<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

final class ProductApiTest extends TestCase
{
    // use RefreshDatabase;

    public function test_product_crud_with_auth(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
                'name' => 'P1',
                'description' => 'D',
                'price' => 9.99,
                'stock' => 10,
            ])->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                ],
            ]);
    }
}
