<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getAllProducts(): Collection|LengthAwarePaginator
    {
        $page = request()->get('page');
        $perPage = request()->get('per_page', 10);

        if ($page) {
            return Product::paginate($perPage, ['*'], 'page', $page);
        } else {
            return Product::all();
        }
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }
}
