<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {
    }
    
    public function index()
    {
        $categories = $this->categoryService->getCategories();

        return CategoryResource::collection($categories);
    }
}
