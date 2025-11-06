<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Services\ProductService;
use App\Services\UserService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\User\UploadUserSignatureRequest;

class ProductController extends ApiController
{

    public function __construct(
        protected ProductService $service,
        protected UserService $userService
    ) {}

    private $fields = [
        'name',
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

    public function exportPdf()
    {
        try {

            $products = $this->service->getAllProducts();

            $pdf = Pdf::loadView('pdf.products', ['products' => $products]);

            // Generate unique filename with timestamp
            $filename = 'products_' . now()->format('Y-m-d_His') . '.pdf';

            // Save PDF to public storage
            Storage::disk('public')->put('exports/' . $filename, $pdf->output());

            // Generate download URL
            $downloadUrl = asset('storage/exports/' . $filename);

            return $this->respondSuccess([
                'message' => 'PDF exported successfully',
                'download_url' => $downloadUrl,
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function exportExcel()
    {
        try {
            $products = $this->service->getAllProducts();
            $filename = 'products_' . now()->format('Y-m-d_His') . '.xlsx';
            Excel::store(new ProductExport($products), 'exports/' . $filename, 'public');
            return $this->respondSuccess([
                'message' => 'PDF exported successfully',
                'download_url' => asset('storage/exports/' . $filename),
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
