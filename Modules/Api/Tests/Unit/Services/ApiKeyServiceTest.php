<?php

declare(strict_types = 1);

namespace Modules\Api\Tests\Unit\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Api\Entities\ApiKey;
use Modules\Api\Repositories\ApiKeyRepository;
use Modules\Api\Services\ApiKeyService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiKeyServiceTest extends TestCase
{
    use WithFaker;

    /**
     * @group api
     * @group api_key
     *
     * @throws BindingResolutionException
     */
    public function testGetPaginate(): void
    {
        $this->partialMock(ApiKeyRepository::class, function ($mock) {
            $mock->shouldReceive('paginate')
                ->once()
                ->andReturn(new LengthAwarePaginator([], 0, 15));
        });

        $result = $this->getTestClassInstance()->getPaginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /**
     * @group api
     * @group api_key
     *
     * @throws BindingResolutionException
     */
    public function testCreateNew(): void
    {
        $title = $this->faker->title;

        $this->partialMock(ApiKeyRepository::class, function ($mock) use ($title) {
            $apiKey = factory(ApiKey::class)->make([
                'title' => $title
            ]);
            $mock->shouldReceive('create')
                ->once()
                ->andReturn($apiKey);
        });

        $result = $this->getTestClassInstance()->createNew($title);

        $this->assertInstanceOf(ApiKey::class, $result);
    }

    /**
     * @return ApiKeyService
     * @throws BindingResolutionException
     */
    private function getTestClassInstance(): ApiKeyService
    {
        return $this->app->make(ApiKeyService::class);
    }
}
