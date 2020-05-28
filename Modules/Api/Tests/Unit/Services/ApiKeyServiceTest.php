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
     * @group api
     * @group api_key
     *
     * @throws BindingResolutionException
     */
    public function testUpdateById(): void
    {
        /** @var ApiKey $appKey */
        $appKey = factory(ApiKey::class)->make([
            'id' => 1,
        ]);

        $this->partialMock(ApiKeyRepository::class, function ($mock) {
            $mock->shouldReceive('update')
                ->once()
                ->andReturn(1);
        });

        $this->getTestClassInstance()->updateById($appKey->id, $appKey->title, $appKey->active);
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
