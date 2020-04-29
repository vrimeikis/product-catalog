<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use App\Enum\ProductTypeEnum;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Product;
use App\ProductImage;
use App\Services\ImagesManager;
use App\Supply;
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
        $products = Product::query()
            ->with(['images', 'categories'])
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

        $suppliers = Supply::query()->pluck('title', 'id');

        $types = ProductTypeEnum::options();

        return view('product.form', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'types' => $types,
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
        $supplierIds = $request->getSuppliers();

        /** @var Product $product */
        $product = Product::query()->create($data);
        $product->categories()->sync($catIds);
        $product->suppliers()->sync($supplierIds);

        ImagesManager::saveMany($product, $request->getImages(), ProductImage::class,
            'file', ImagesManager::PATH_PRODUCT);

        return redirect()->route('products.index')
            ->with('status', 'Product created.');
    }

    /**
     * @param int $id
     *
     * @return View
     */
    public function edit(int $id): View
    {
        /** @var Product $product */
        $product = Product::query()->find($id);
        $productCategoryIds = $product->categories()->pluck('id')->toArray();
        $productSupplierIds = $product->suppliers()->pluck('id')->toArray();
        /** @var Collection|Category[] $categories */
        $categories = Category::query()->get();
        $suppliers = Supply::query()->pluck('title', 'id');
        $types = ProductTypeEnum::options();

        return view('product.form', [
            'product' => $product,
            'categoryIds' => $productCategoryIds,
            'supplierIds' => $productSupplierIds,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'types' => $types,
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
        $suppliersIds = $request->getSuppliers();

        $product->update($data);
        $product->categories()->sync($catIds);
        $product->suppliers()->sync($suppliersIds);

        ImagesManager::saveMany($product, $request->getImages(), ProductImage::class,
            'file', ImagesManager::PATH_PRODUCT, $request->getDeleteImages());

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
