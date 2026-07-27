<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * عرض كل المنتجات (متاح للجميع المسجلين)
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
                'last_page'    => $products->lastPage(),
            ],
        ]);
    }

    /**
     * إنشاء منتج جديد (Admin فقط - محمي من الـ routes)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|string',
            'is_featured' => 'boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    /**
     * عرض منتج واحد
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    /**
     * تحديث منتج (Admin فقط - محمي من الـ routes)
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'stock'       => 'sometimes|required|integer|min:0',
            'image'       => 'nullable|string',
            'is_featured' => 'boolean',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $product->update($validated);

        return response()->json($product->load('category'));
    }

    /**
     * حذف منتج (Admin فقط - محمي من الـ routes)
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
