<?php

declare(strict_types = 1);

namespace Modules\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface RepositoryContract
 * @package Modules\Core\Contracts
 */
interface RepositoryContract
{
    /**
     * @return string
     */
    public function model(): string;

    /**
     * @return Builder
     */
    public function makeQuery(): Builder;
}