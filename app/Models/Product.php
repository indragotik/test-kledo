<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'picture_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}
