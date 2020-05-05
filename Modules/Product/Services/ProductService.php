<?php

declare(strict_types = 1);

namespace Modules\Product\Services;

use App\DTO\Abstracts\CollectionDTO;
use App\DTO\Abstracts\PaginateLengthAwareDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\Product\DTO\ProductDTO;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Exceptions\ModelRelationMissingException;
use Modules\Product\Repositories\ProductRepository;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginateWithRelationsAdmin(): LengthAwarePaginator
    {
        return $this->productRepository->paginateWithRelations(['images', 'categories']);
    }

    /**
     * @param array $data
     * @param array $catIds
     * @param array $supplierIds
     * @param array $images
     * @return Product
     * @throws ModelRelationMissingException
     */
    public function createWithRelationsAdmin(
        array $data,
        array $catIds = [],
        array $supplierIds = [],
        array $images = []
    ): Product
    {
        /** @var Product $product */
        $product = $this->productRepository->create($data);
        $product->categories()->sync($catIds);
        $product->suppliers()->sync($supplierIds);

        ImagesManager::saveMany($product, $images, ProductImage::class,
            'file', ImagesManager::PATH_PRODUCT);

        return $product;
    }

    /**
     * @param array $data
     * @param int $id
     * @param bool $deleteImages
     * @return Product
     * @throws ModelRelationMissingException
     */
    public function updateWithRelationsAdmin(
        array $data,
        int $id,
        bool $deleteImages = false
    ): Product
    {
        $this->productRepository->update($data, $id);

        $product = $this->getById($id);

        $product->categories()->sync(Arr::get($data, 'categories', []));
        $product->suppliers()->sync(Arr::get($data, 'suppliers', []));

        $images = Arr::get($data, 'images', []);

        ImagesManager::saveMany($product, $images, ProductImage::class,
            'file', ImagesManager::PATH_PRODUCT, $deleteImages);

        return $product;
    }

    /**
     * @param int $id
     * @return Product|Model
     */
    public function getById(int $id): Product {
        return $this->productRepository->findOrFail($id);
    }

    /**
     * @param int $id
     * @throws ModelRelationMissingException
     */
    public function delete(int $id): void
    {
        $product = $this->getById($id);

        ImagesManager::deleteAll($product);

        $this->productRepository->delete($id);
    }

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

    /**
     * @return PaginateLengthAwareDTO
     */
    public function getPaginateForApi(): PaginateLengthAwareDTO
    {
        $productsDTO = new CollectionDTO();

        $products = Product::query()
            ->with(['images', 'categories'])
            ->where('active', '=', 1)
            ->paginate();

        foreach ($products as $product) {
            $productsDTO->pushItem(new ProductDTO($product));
        }

        return (new PaginateLengthAwareDTO($products))->setData($productsDTO);
    }

    /**
     * @param string $slug
     * @return PaginateLengthAwareDTO
     */
    public function getPaginateByCategorySlugForApi(string $slug): PaginateLengthAwareDTO
    {
        $productsDTO = new CollectionDTO();

        $products = Product::query()
            ->with(['images', 'categories'])
            ->where('active', '=', 1)
            ->whereHas('categories', function (Builder $query) use ($slug) {
                $query->where('slug', '=', $slug);
            })
            ->paginate();

        foreach ($products as $product) {
            $productsDTO->pushItem(new ProductDTO($product));
        }

        return (new PaginateLengthAwareDTO($products))->setData($productsDTO);
    }
}