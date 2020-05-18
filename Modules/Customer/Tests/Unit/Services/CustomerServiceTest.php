<?php

declare(strict_types = 1);

namespace Modules\Customer\Tests\Unit\Services;

use App\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Customer\DTO\CustomerFullDTO;
use Modules\Customer\Exceptions\CustomerException;
use Modules\Customer\Services\CustomerService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group service
     * @group customer
     * @group customer_service
     *
     * @throws BindingResolutionException
     * @throws CustomerException
     */
    public function testSuccessGetMyInfoApi(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->make();
        $this->actingAs($customer, 'api');

        $customerDTO = $this->getTestClassInstance()->getMyInfoApi();

        $this->assertInstanceOf(CustomerFullDTO::class, $customerDTO);

        $this->assertEquals(new CustomerFullDTO($customer), $customerDTO);
    }

    /**
     * @group service
     * @group customer
     * @group customer_service
     *
     * @throws BindingResolutionException
     */
    public function testThrowCustomerExceptionOnGetMyInfoApi(): void
    {
        $this->expectException(CustomerException::class);
        $this->expectExceptionMessage(CustomerException::noCustomer()->getMessage());

        $this->getTestClassInstance()->getMyInfoApi();
    }

    /**
     * @group service
     * @group customer
     * @group customer_service
     *
     * @throws BindingResolutionException
     * @throws CustomerException
     */
    public function testUpdateMyInfoApi(): void
    {
        $updateData = [
            'name' => 'Josue',
        ];

        $customer = factory(User::class)->create([
            'name' => 'John',
        ]);

        $this->actingAs($customer, 'api');

        $result = $this->getTestClassInstance()->updateMyInfoApi($updateData);

        $this->assertEquals(1, $result);
    }

    /**
     * @group service
     * @group customer
     * @group customer_service
     *
     * @throws BindingResolutionException
     */
    public function testUpdateInfo(): void
    {
        $updateData = [
            'name' => 'Josue',
        ];

        /** @var User $customer */
        $customer = factory(User::class)->create([
            'name' => 'John',
        ]);

        $result = $this->getTestClassInstance()->updateInfo($updateData, $customer->id);

        $this->assertEquals(1, $result);
    }

    /**
     * @group service
     * @group customer
     * @group customer_service
     *
     * @throws BindingResolutionException
     * @throws CustomerException
     */
    public function testSuccessDeleteMe(): void
    {
        $customer = factory(User::class)->create();

        $this->actingAs($customer, 'api');

        $result = $this->getTestClassInstance()->deleteMe();

        $this->assertEquals(1, $result);
    }

    /**
     * @group service
     * @group customer
     * @group customer_service
     *
     * @throws BindingResolutionException
     */
    public function testThrowCustomerExceptionOnDeleteMe(): void
    {
        $this->expectException(CustomerException::class);
        $this->expectExceptionMessage(CustomerException::noCustomer()->getMessage());

        $this->getTestClassInstance()->deleteMe();
    }

    /**
     * @return CustomerService
     * @throws BindingResolutionException
     */
    private function getTestClassInstance(): CustomerService
    {
        return $this->app->make(CustomerService::class);
    }
}
