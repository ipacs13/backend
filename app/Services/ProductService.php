<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getAllProducts(): Collection|LengthAwarePaginator
    {
        $page = request()->get('page');
        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');

        $query = Product::query();

        $query->when($search, function ($query, $search) {
            $needle = strtolower($search);
            return $query->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $needle . '%']);
        });

        if ($page) {
            return $query->paginate($perPage, ['*'], 'page', $page);
        } else {
            return $query->get();
        }
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        DB::beginTransaction();
        try {
            $product->update($data);
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
