<?php

declare(strict_types = 1);

namespace Modules\Api\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Api\Entities\ApiKey;
use Modules\Core\Repositories\Repository;

/**
 * Class ApiKeyRepository
 * @package Modules\Api\Repositories
 */
class ApiKeyRepository extends Repository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return ApiKey::class;
    }

    /**
     * @param string $appKey
     * @return ApiKey|Model|null
     */
    public function getByAppKey(string $appKey): ?ApiKey
    {
        return $this->makeQuery()
            ->where('app_key', '=', $appKey)
            ->first();
    }
}