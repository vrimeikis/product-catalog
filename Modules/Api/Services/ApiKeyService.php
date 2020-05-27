<?php

declare(strict_types = 1);

namespace Modules\Api\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Api\Entities\ApiKey;
use Modules\Api\Repositories\ApiKeyRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class ApiKeyService
 * @package Modules\Api\Services
 */
class ApiKeyService
{
    /**
     * @var ApiKeyRepository
     */
    private $apiKeyRepository;

    /**
     * ApiKeyService constructor.
     * @param ApiKeyRepository $apiKeyRepository
     */
    public function __construct(ApiKeyRepository $apiKeyRepository)
    {
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginate(): LengthAwarePaginator
    {
        return $this->apiKeyRepository->paginate();
    }

    /**
     * @param string $title
     * @param bool $active
     * @return ApiKey|Model
     */
    public function createNew(string $title, bool $active = false): ApiKey
    {
        return $this->apiKeyRepository->create([
            'title' => $title,
            'app_key' => $this->generateAppKey(),
            'active' => $active
        ]);
    }

    /**
     * @return UuidInterface
     */
    private function generateAppKey(): UuidInterface
    {
        return Uuid::uuid4();
    }
}