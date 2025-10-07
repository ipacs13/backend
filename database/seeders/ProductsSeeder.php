<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description 1',
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description 2',
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description 3',
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
