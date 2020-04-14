<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Product;
use App\ProductImage;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
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
    public function index(): View
    {
        /** @var LengthAwarePaginator $products */
        $products = Product::query()->with(['images', 'categories'])
            ->paginate();

        return view('product.product-list', [
            'list' => $products,
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        /** @var Collection|Category[] $categories */
        $categories = Category::query()->get();

        return view('product.form', [
            'categories' => $categories,
        ]);
    }

    /**
     * @param ProductStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $data = $request->getData();

        $catIds = $request->getCategories();

        /** @var Product $product */
        $product = Product::query()->create($data);
        $product->categories()->sync($catIds);

        if ($uploadedFiles = $request->getImages()) {
            $productImages = [];
            foreach ($uploadedFiles as $uploadedFile) {
                $imagePath = $uploadedFile->store('products');
                $productImages[] = new ProductImage(['file' => $imagePath]);
            }
            $product->images()->saveMany($productImages);
        }

        return redirect()->route('products.index');
    }

    /**
     * @param int $id
     *
     * @return View
     */
    public function edit(int $id): View
    {
        // SELECT * FROM products WHERE id = ?
        /** @var Product $product */
        $product = Product::query()->find($id);
        $productCategoryIds = $product->categories()->pluck('id')->toArray();
        /** @var Category $categories */
        $categories = Category::query()->get();

        return view('product.form', [
            'product' => $product,
            'categoryIds' => $productCategoryIds,
            'categories' => $categories,
        ]);
    }

    /**
     * @param ProductUpdateRequest $request
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $data = $request->getData();
        $catIds = $request->getCategories();

        $product->update($data);
        $product->categories()->sync($catIds);

        if ($request->getDeleteImages()) {
            Storage::delete(
                $product->images->pluck('file')->toArray()
            );

            $product->images()->delete();
        }

        if ($uploadedFiles = $request->getImages()) {
            $productImages = [];
            foreach ($uploadedFiles as $uploadedFile) {
                $imagePath = $uploadedFile->store('products');
                $productImages[] = new ProductImage(['file' => $imagePath]);
            }
            $product->images()->saveMany($productImages);
        }

        return redirect()->route('products.index')
            ->with('status', 'Product updated.');
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Product $product): RedirectResponse
    {
        Storage::delete(
            $product->images->pluck('file')->toArray()
        );

        $product->delete();

        return redirect()->route('products.index')
            ->with('status', 'Product deleted.');
    }

}
