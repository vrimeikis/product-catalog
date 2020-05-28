<?php

declare(strict_types = 1);

namespace Modules\Api\Services;

use Illuminate\Http\Request;
use Modules\Api\Entities\ApiKey;
use Modules\Api\Exceptions\AppKeyException;
use Modules\Api\Repositories\ApiKeyRepository;

/**
 * Class AppKeyManager
 * @package Modules\Api\Services
 */
class AppKeyManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var ApiKeyRepository
     */
    private $apiKeyRepository;

    /**
     * @var null
     */
    private $appKey = null;

    /**
     * @var ApiKey|null
     */
    private $appKeyData = null;

    /**
     * AppKeyManager constructor.
     * @param Request $request
     * @param ApiKeyRepository $apiKeyRepository
     */
    public function __construct(Request $request, ApiKeyRepository $apiKeyRepository)
    {
        $this->request = $request;
        $this->apiKeyRepository = $apiKeyRepository;

        $this->setAppKeyFromRequest();
        $this->setAppKeyData();
    }

    /**
     * @return bool
     * @throws AppKeyException
     */
    public function checkRequestApiKey(): bool
    {
        $appKey = $this->getAppKey();

        if (empty($appKey)) {
            throw AppKeyException::missingAppKey();
        }

        if (!$this->appKeyExistsAndActive()) {
            throw AppKeyException::keyExpired();
        }

        return true;
    }

    /**
     * @return void
     */
    private function setAppKeyFromRequest(): void
    {
        $this->appKey = $this->request->header('PRIVATE-API-KEY', null);
    }

    /**
     * @return string|null
     */
    private function getAppKey(): ?string
    {
        return $this->appKey;
    }

    /**
     * @return bool
     */
    private function appKeyExistsAndActive(): bool
    {
        return !empty($this->appKeyData) && $this->appKeyData->active;
    }

    /**
     * @return void
     */
    private function setAppKeyData(): void
    {
        if ($this->getAppKey() !== null) {
            $this->appKeyData = $this->apiKeyRepository->getByAppKey($this->getAppKey());
        }
    }
}