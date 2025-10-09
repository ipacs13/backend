<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Products\ProductCollection;
use App\Services\ProductService;

class ProductController extends ApiController
{

    public function __construct(protected ProductService $service) {}


    public function index()
    {
        try {
            $products = $this->service->getAllProducts();
            return new ProductCollection($products);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
