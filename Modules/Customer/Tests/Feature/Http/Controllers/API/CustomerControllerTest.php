<?php

declare(strict_types = 1);

namespace Modules\Customer\Tests\Feature\Http\Controllers\API;

use App\User;
use Illuminate\Http\JsonResponse;
use Mockery;
use Modules\Customer\DTO\CustomerFullDTO;
use Modules\Customer\Http\Requests\API\CustomerUpdateRequest;
use Modules\Customer\Services\CustomerService;
use Tests\TestCase;

/**
 * Class CustomerControllerTest
 * @package Modules\Customer\Tests\Feature\Http\Controllers\API
 */
class CustomerControllerTest extends TestCase
{
    /**
     * @group customer
     * @group api
     * @group customer_api
     */
    public function testShow(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->make();
        $this->actingAs($customer, 'api');

        $this->partialMock(CustomerService::class, function ($mock) use ($customer) {
            $mock->shouldReceive('getMyInfoApi')
                ->once()
                ->andReturn(new CustomerFullDTO($customer));
        });

        $response = $this->get(route('api.customer.show'), [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
    }

    /**
     * @group customer
     * @group api
     * @group customer_api
     */
    public function testFailUpdateValidation(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->make();

        $this->actingAs($customer, 'api');

        $response = $this->put(route('api.customer.update'), [], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @group customer
     * @group api1
     * @group customer_api
     */
    public function testSuccessUpdate(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->make();

        $requestData = [
            'name' => $customer->name,
            'email' => $customer->email,
        ];

        $this->instance(CustomerUpdateRequest::class, Mockery::mock(CustomerUpdateRequest::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('getData')
                ->once()
                ->andReturn($requestData);
        }));

        $this->partialMock(CustomerService::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('updateMyInfoApi')
                ->once()
                ->with($requestData)
                ->andReturn(1);
        });

        $this->actingAs($customer, 'api');

        $response = $this->put(route('api.customer.update'), [
            'first_name' => $customer->name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'mobile' => $customer->mobile,
            'address' => $customer->address,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group customer
     * @group api
     * @group customer_api
     */
    public function testDestroy(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->make();

        $this->actingAs($customer, 'api');

        $this->partialMock(CustomerService::class, function ($mock) {
            $mock->shouldReceive('deleteMe')
                ->once();
        });

        $response = $this->delete(route('api.customer.destroy'), [], [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
    }
}
