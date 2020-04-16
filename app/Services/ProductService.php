<?php

declare(strict_types = 1);

namespace App\Services;

use App\DTO\Abstracts\CollectionDTO;
use App\DTO\ProductDTO;
use App\Product;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    /**
     * @param string $slug
     * @return ProductDTO
     */
    public function getBySlugForApi(string $slug): ProductDTO
    {
        $product = Product::query()
            ->where('active', '=', 1)
            ->where('slug', '=', $slug)
            ->firstOrFail();

        return new ProductDTO($product);
    }

    /**
     * @return CollectionDTO
     */
    public function getAllForApi(): CollectionDTO
    {
        $productsDTO = new CollectionDTO();

        $products = Product::query()
            ->with(['images', 'categories'])
            ->where('active', '=', 1)
            ->get();

        foreach ($products as $product) {
            $productsDTO->pushItem(new ProductDTO($product));
        }

        return $productsDTO;
    }
}