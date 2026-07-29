<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {
    }

    
    public function index(Request $request)
    {
        $filters = $request->only([
            'category_id',
            'search',
            'min_price',
            'max_price',
        ]);

        $products = $this->productService->getProducts($filters);

        return ProductResource::collection($products);
    }
}
