<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // SELECT * FROM products LIMITS 15, 30
        /** @var LengthAwarePaginator $products */
        $products = Product::query()->paginate();

        return view('product.product-list', [
            'list' => $products,
        ]);
    }
}
