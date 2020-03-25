<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        // SELECT * FROM products LIMITS 15, 30
        /** @var LengthAwarePaginator $products */
        $products = Product::query()->paginate();

        return view('product.product-list', [
            'list' => $products,
        ]);
    }

    public function create() {
        return view('product.create');
    }

    public function store(Request $request) {
        $data = $request->only(
            'title',
            'description',
            'price'
        );

        Product::query()->create($data);

        return redirect()->route('products.index');

    }

}
