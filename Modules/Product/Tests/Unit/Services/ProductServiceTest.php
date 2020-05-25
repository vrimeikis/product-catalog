<?php

namespace Modules\Product\Tests\Unit\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\DTO\PaginateLengthAwareDTO;
use Modules\Product\DTO\ProductDTO;
use Modules\Product\Entities\Product;
use Modules\Product\Exceptions\ModelRelationMissingException;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\ImagesManager;
use Modules\Product\Services\ProductService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductServiceTest extends TestCase
{
    use WithFaker;
    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     */
    public function testGetPaginateWithRelationsAdmin(): void
    {
        $this->partialMock(ProductRepository::class, function ($mock) {
            $mock->shouldReceive('paginateWithRelations')
                ->once()
                ->with(['images', 'categories']);
        });

        $this->getTestClassInstance()->getPaginateWithRelationsAdmin();
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     * @throws ModelRelationMissingException
     */
    public function testCreatWithRelationsAdmin(): void
    {
        /** @var Product $product */
        $product = factory(Product::class)->make();

        $this->partialMock(ProductRepository::class, function ($mock) use ($product) {
            $mock->shouldReceive('createWithManyToManyRelations')
                ->once()
                ->with(
                    $product->toArray(),
                    [
                        'categories' => [],
                        'suppliers' => [],
                    ]
                )
                ->andReturn($product);
        });

        $mock = \Mockery::namedMock(ImagesManager::class, ImagesManagerStub::class);
        $mock->shouldReceive('saveMany')
            ->once();

        $result = $this->getTestClassInstance()
            ->createWithRelationsAdmin($product->toArray());

        $this->assertInstanceOf(Product::class, $result);
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     * @throws ModelRelationMissingException
     */
    public function testUpdateWithRelationsAdmin(): void
    {
        /** @var Product $product */
        $product = factory(Product::class)->make(['id' => 1]);
        $data = [];

        $this->partialMock(ProductRepository::class, function ($mock) use ($product) {
            $mock->shouldReceive('updateWithManyToManyRelations')
                ->once()
                ->andReturn($product);
        });

        $mock = \Mockery::namedMock(ImagesManager::class, ImagesManagerStub::class);
        $mock->shouldReceive('saveMany')
            ->once();

        $result = $this->getTestClassInstance()->updateWithRelationsAdmin($data, $product->id);

        $this->assertInstanceOf(Product::class, $result);
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     */
    public function testGetById(): void
    {
        $id = mt_rand(1, 10);

        $this->partialMock(ProductRepository::class, function ($mock) use ($id) {
            $product = factory(Product::class)->make();

            $mock->shouldReceive('findOrFail')
                ->once()
                ->with($id)
                ->andReturn($product);
        });

        $this->getTestClassInstance()->getById($id);
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     * @throws ModelRelationMissingException
     */
    public function testDelete(): void
    {
        $id = mt_rand(1, 10);

        $this->partialMock(ProductRepository::class, function ($mock) use ($id) {
            $product = factory(Product::class)->make(['id' => $id]);

            $mock->shouldReceive('findOrFail')
                ->once()
                ->with($id)
                ->andReturn($product);

            $mock->shouldReceive('delete')
                ->once()
                ->with($id);
        });


        $mock = \Mockery::namedMock(ImagesManager::class, ImagesManagerStub::class);
        $mock->shouldReceive('deleteAll')
            ->once();

        $this->getTestClassInstance()->delete($id);
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     */
    public function testGetBySlugApi(): void
    {
        $id = mt_rand(1, 10);
        /** @var Product $product */
        $product = factory(Product::class)->make(['id' => $id]);

        $this->partialMock(ProductRepository::class, function ($mock) use ($product) {
            $mock->shouldReceive('getBySlug')
                ->once()
                ->with($product->slug)
                ->andReturn($product);
        });

        $result = $this->getTestClassInstance()->getBySlugForApi($product->slug);

        $this->assertInstanceOf(ProductDTO::class, $result);
        $this->assertEquals(new ProductDTO($product), $result);
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     */
    public function testGetPaginateForApi(): void
    {
        $this->partialMock(ProductRepository::class, function ($mock) {
            $data = new LengthAwarePaginator([], 0, 10);

            $mock->shouldReceive('paginateWithRelations')
                ->once()
                ->with(['images', 'categories'], true)
                ->andReturn($data);
        });

        $result = $this->getTestClassInstance()->getPaginateForApi();

        $this->assertInstanceOf(PaginateLengthAwareDTO::class, $result);
    }

    /**
     * @group service
     * @group product
     *
     * @throws BindingResolutionException
     */
    public function testGetPaginateByCategorySlugForApi(): void
    {
        $slug = $this->faker->slug;

        $this->partialMock(ProductRepository::class, function ($mock) {
            $mock->shouldReceive('getByCategorySlug')
                ->once()
                ->andReturn(new LengthAwarePaginator([], 0, 10));
        });

        $result = $this->getTestClassInstance()->getPaginateByCategorySlugForApi($slug);

        $this->assertInstanceOf(PaginateLengthAwareDTO::class, $result);
    }

    /**
     * @return ProductService
     * @throws BindingResolutionException
     */
    private function getTestClassInstance(): ProductService
    {
        return $this->app->make(ProductService::class);
    }
}

class ImagesManagerStub
{
    const PATH_PRODUCT = 'product';
}
