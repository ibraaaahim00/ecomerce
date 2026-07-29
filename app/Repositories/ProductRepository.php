<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = Product::with('category');


        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }


        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }


        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }


        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }


        return $query->latest()->get();
    }
}
