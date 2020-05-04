<?php

declare(strict_types = 1);

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @param array|string[] $columns
     * @return LengthAwarePaginator
     */
    public function paginateWithRelations(array $with = [], array $columns = ['*']): LengthAwarePaginator
    {
        return $this->makeQuery()->with($with)
            ->paginate(self::DEFAULT_PER_PAGE, $columns);
    }
}