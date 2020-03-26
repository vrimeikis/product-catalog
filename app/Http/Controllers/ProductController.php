<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductStoreRequest;
use App\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        /** @var Collection|Category[] $categories */
        $categories = Category::query()->get();

        return view('product.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * @param ProductStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ProductStoreRequest $request): RedirectResponse {
        $data = $request->only(
            'title',
            'description',
            'price'
        );

        $catIds = $request->input('categories');

        /** @var Product $product */
        $product = Product::query()->create($data);
        $product->categories()->sync($catIds);

        return redirect()->route('products.index');
    }

    /**
     * @param int $id
     *
     * @return View
     */
    public function edit(int $id): View {
        // SELECT * FROM products WHERE id = ?
        /** @var Product $product */
        $product = Product::query()->find($id);
        $productCategoryIds = $product->categories()->pluck('id')->toArray();
        /** @var Category $categories */
        $categories = Category::query()->get();

        return view('product.edit', [
            'product' => $product,
            'categoryIds' => $productCategoryIds,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse {
        $data = $request->only('title', 'description', 'price');
        $catIds = $request->input('categories');

        $product->update($data);
        $product->categories()->sync($catIds);

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
