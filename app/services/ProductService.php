<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }


    public function getProducts(array $filters = []): Collection
    {
        return $this->productRepository->getAll($filters);
    }
}
