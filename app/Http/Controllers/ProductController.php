<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    public function index()
    {
        // SELECT * FROM products LIMITS 15, 30
        /** @var Collection $products */
        $products = Product::query()->paginate();

        return view('product.product-list', [
            'list' => $products,
        ]);
    }
}
