<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * عرض كل الكاتيجوريز
     */
    public function index()
    {
        return response()->json(Category::latest()->get());
    }

    /**
     * إنشاء كاتيجوري جديدة (Admin فقط - محمي من الـ routes)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|string',
        ]);

        $category = Category::create([
            'name'  => $validated['name'],
            'slug'  => Str::slug($validated['name']),
            'image' => $validated['image'] ?? null,
        ]);

        return response()->json($category, 201);
    }

    /**
     * عرض كاتيجوري مع منتجاتها
     */
    public function show(Category $category)
    {
        return response()->json($category->load('products'));
    }

    /**
     * تحديث كاتيجوري (Admin فقط - محمي من الـ routes)
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|string',
        ]);

        $category->update([
            'name'  => $validated['name'],
            'slug'  => Str::slug($validated['name']),
            'image' => $validated['image'] ?? $category->image,
        ]);

        return response()->json($category);
    }

    /**
     * حذف كاتيجوري (Admin فقط - محمي من الـ routes)
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
