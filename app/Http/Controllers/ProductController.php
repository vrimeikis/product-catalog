<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @return View
     */
    public function index(): View {
        // SELECT * FROM products LIMITS 15, 30
        /** @var LengthAwarePaginator $products */
        $products = Product::query()->paginate();

        return view('product.product-list', [
            'list' => $products,
        ]);
    }

    /**
     * @return View
     */
    public function create(): View {
        return view('product.create');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
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
     * @return View
     */
    public function edit(int $id): View {
        // SELECT * FROM products WHERE id = ?
        $product = Product::query()->find($id);

        return view('product.edit', ['product' => $product]);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse {
        $data = $request->only('title', 'description', 'price');

        Product::query()
            ->where('id', '=', $id)
            ->update($data);

        return redirect()->route('products.index');
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse {
        // DELETE FROM products WHERE id = ?
        Product::query()
            ->where('id', '=', $id)
            ->delete();

        return redirect()->route('products.index');
    }

}
