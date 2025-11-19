<?php

namespace App\DTO;

use App\Models\Product;

final class ProductDTO
{
    public int $id;
    public string $name;
    public ?string $description;
    public string $price;
    public int $stock;
    public ?string $picture_url;

    public function __construct(int $id, string $name, ?string $description, string $price, int $stock, ?string $picture_url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->picture_url = $picture_url;
    }

    public static function fromModel(Product $p): self
    {
        return new self($p->id, $p->name, $p->description, (string) $p->price, (int) $p->stock, $p->picture_url);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'picture_url' => $this->picture_url,
        ];
    }
}
