<?php

declare(strict_types = 1);

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Repositories\Repository;
use Modules\Product\Entities\Product;

/**
 * Class ProductRepository
 * @package Modules\Product\Repositories
 */
class ProductRepository extends Repository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Product::class;
    }

    /**
     * @param array $with
     * @param bool $active
     * @param array|string[] $columns
     * @return LengthAwarePaginator
     */
    public function paginateWithRelations(array $with = [], bool $active = false, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->withRelationsBuilder($with, $active)->paginate(self::DEFAULT_PER_PAGE, $columns);
    }

    /**
     * @param string $categorySlug
     * @return LengthAwarePaginator
     */
    public function getByCategorySlug(string $categorySlug): LengthAwarePaginator
    {
        return $this->withRelationsBuilder(['images', 'categories', 'suppliers'], true)
            ->whereHas('categories', function (Builder $query) use ($categorySlug) {
                $query->where('slug', '=', $categorySlug);
            })
            ->paginate();
    }

    /**
     * @param string $slug
     * @return Product|Model
     */
    public function getBySlug(string $slug): Product
    {
        return $this->withRelationsBuilder([], true)
            ->where('slug', '=', $slug)
            ->firstOrFail();
    }

    /**
     * @param array $data
     * @param array $relatedData
     * @return Product
     */
    public function createWithManyToManyRelations(array $data, array $relatedData = []): Product
    {
        /** @var Product $product */
        $product = $this->create($data);

        foreach ($relatedData as $relation => $ids) {
            if (!method_exists($product, $relation)) {
                continue;
            }

            $product->$relation()->sync($ids);
        }

        return $product;
    }

    /**
     * @param array $with
     * @param bool $active
     * @return Builder
     */
    private function withRelationsBuilder(array $with = [], bool $active = false): Builder
    {
        $query = $this->makeQuery()->with($with);

        if ($active === true) {
            $query->where('active', '=', true);
        }

        return $query;
    }

}