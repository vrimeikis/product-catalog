<?php

declare(strict_types = 1);

namespace Modules\Api\Repositories;

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
}