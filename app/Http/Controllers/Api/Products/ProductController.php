<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends ApiController
{

    public function __construct(protected ProductService $service) {}

    private $fields = [
        'description',
    ];

    public function index(): ProductCollection|JsonResponse
    {
        try {
            $products = $this->service->getAllProducts();
            return new ProductCollection($products);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function show(Product $product): ProductResource|JsonResponse
    {
        try {
            return new ProductResource($product);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->service->createProduct($request->validated());
            return new ProductResource($product);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $product = $this->service->updateProduct($product, $request->only($this->fields));
            return new ProductResource($product);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->respondSuccess(["message" => "Product deleted successfully"]);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
