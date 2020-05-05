<?php

declare(strict_types = 1);

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Product\Enum\ProductTypeEnum;
use Modules\Product\Exceptions\ModelRelationMissingException;
use Modules\Product\Http\Requests\ProductStoreRequest;
use Modules\Product\Http\Requests\ProductUpdateRequest;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\SupplyRepository;
use Modules\Product\Services\ProductService;
use ReflectionException;

/**
 * Class ProductController
 * @package Modules\Product\Http\Controllers\Admin
 */
class ProductController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var SupplyRepository
     */
    private $supplyRepository;
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     * @param CategoryRepository $categoryRepository
     * @param SupplyRepository $supplyRepository
     */
    public function __construct(
        ProductService $productService,
        CategoryRepository $categoryRepository,
        SupplyRepository $supplyRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->supplyRepository = $supplyRepository;
        $this->productService = $productService;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $products = $this->productService->getPaginateWithRelationsAdmin();

        return view('product::product.list', [
            'list' => $products,
        ]);
    }

    /**
     * @return View
     * @throws ReflectionException
     */
    public function create(): View
    {
        $categories = $this->categoryRepository->all(['id', 'title']);
        $suppliers = $this->supplyRepository->pluck('title', 'id');

        $types = ProductTypeEnum::enum();

        return view('product::product.form', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'types' => $types,
        ]);
    }

    /**
     * @param ProductStoreRequest $request
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(ProductStoreRequest $request): RedirectResponse
    {
        try {
            $this->productService->createWithRelationsAdmin(
                $request->getData(),
                $request->getCategories(),
                $request->getSuppliers(),
                $request->getImages()
            );
        } catch (ModelRelationMissingException $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', $exception->getMessage());
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Something wrong.');
        }

        return redirect()->route('products.index')
            ->with('status', 'Product created.');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|RedirectResponse|View
     */
    public function edit(int $id)
    {
        try {
            $product = $this->productService->getById($id);
            $productCategoryIds = $product->categories()->pluck('id')->toArray();
            $productSupplierIds = $product->suppliers()->pluck('id')->toArray();
            $categories = $this->categoryRepository->all(['id', 'title']);
            $suppliers = $this->supplyRepository->pluck('title', 'id');
            $types = ProductTypeEnum::enum();

            return view('product::product.form', [
                'product' => $product,
                'categoryIds' => $productCategoryIds,
                'supplierIds' => $productSupplierIds,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'types' => $types,
            ]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('products.index')
                ->with('danger', 'Record not found.');
        } catch (Exception $exception) {
            return redirect()->route('products.index')
                ->with('danger', 'Something wrong.');
        }
    }

    /**
     * @param ProductUpdateRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(ProductUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $this->productService->updateWithRelationsAdmin(
                $request->getData(),
                $id,
                $request->getDeleteImages()
            );
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Record not found.');
        } catch (ModelRelationMissingException $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', $exception->getMessage());
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Something wrong.');
        }

        return redirect()->route('products.index')
            ->with('status', 'Product updated.');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->productService->delete($id);
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('products.index')
                ->with('danger', 'Record not found.');
        } catch (ModelRelationMissingException $exception) {
            return redirect()->route('products.index')
                ->with('danger', $exception->getMessage());
        } catch (Exception $exception) {
            return redirect()->route('products.index')
                ->with('danger', 'Something wrong.');
        }

        return redirect()->route('products.index')
            ->with('status', 'Product deleted.');
    }
}
