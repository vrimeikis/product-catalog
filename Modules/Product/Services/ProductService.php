<?php

declare(strict_types = 1);

namespace Modules\Product\Services;

use Modules\Core\DTO\CollectionDTO;
use Modules\Core\DTO\PaginateLengthAwareDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
        $relations = [
            'categories' => $catIds,
            'suppliers' => $supplierIds,
        ];

        $product = $this->productRepository->createWithManyToManyRelations($data, $relations);

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
        $product = $this->productRepository->getBySlug($slug);

        return new ProductDTO($product);
    }

    /**
     * @return PaginateLengthAwareDTO
     */
    public function getPaginateForApi(): PaginateLengthAwareDTO
    {
        $productsDTO = new CollectionDTO();

        $products = $this->productRepository->paginateWithRelations(['images', 'categories'], true);

        foreach ($products as $product) {
            $productsDTO->pushItem(new ProductDTO($product));
        }

        return (new PaginateLengthAwareDTO($products))->setData($productsDTO);
    }

    /**
     * @param string $categorySlug
     * @return PaginateLengthAwareDTO
     */
    public function getPaginateByCategorySlugForApi(string $categorySlug): PaginateLengthAwareDTO
    {
        $productsDTO = new CollectionDTO();

        $products = $this->productRepository->getByCategorySlug($categorySlug);

        foreach ($products as $product) {
            $productsDTO->pushItem(new ProductDTO($product));
        }

        return (new PaginateLengthAwareDTO($products))->setData($productsDTO);
    }
}