<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        // SELECT * FROM products LIMITS 15, 30
        /** @var LengthAwarePaginator $products */
        $products = Product::query()->paginate();

        return view('product.product-list', [
            'list' => $products,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('product.create');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $data = $request->only(
            'title',
            'description',
            'price'
        );

        Product::query()->create($data);

        return redirect()->route('products.index');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id) {
        // SELECT * FROM products WHERE id = ?
        $product = Product::query()->find($id);

        return view('product.edit', ['product' => $product]);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id) {
        $data = $request->only('title', 'description', 'price');

        Product::query()
            ->where('id', '=', $id)
            ->update($data);

        return redirect()->route('products.index');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id) {
        // DELETE FROM products WHERE id = ?
        Product::query()
            ->where('id', '=', $id)
            ->delete();

        return redirect()->route('products.index');
    }

}
