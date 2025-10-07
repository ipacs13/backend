<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/products', function () {
    return response()->json([
        'products' => Product::all(),
        'message' => 'Products fetched successfully'
    ]);
});
