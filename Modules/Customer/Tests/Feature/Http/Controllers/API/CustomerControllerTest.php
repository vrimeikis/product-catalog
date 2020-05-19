<?php

declare(strict_types = 1);

namespace Modules\Customer\Tests\Feature\Http\Controllers\API;

use App\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CustomerControllerTest
 * @package Modules\Customer\Tests\Feature\Http\Controllers\API
 */
class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @group customer
     * @group api
     * @group customer_api
     */
    public function testShow(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->create();

        $this->actingAs($customer, 'api');

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
        $customer = factory(User::class)->create();

        $this->actingAs($customer, 'api');

        $response = $this->put(route('api.customer.update'), [], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @group customer
     * @group api
     * @group customer_api
     */
    public function testSuccessUpdate(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->create();

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
        $customer = factory(User::class)->create();

        $this->actingAs($customer, 'api');

        $response = $this->delete(route('api.customer.destroy'), [], [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
    }
}
