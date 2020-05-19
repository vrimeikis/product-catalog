<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature\Http\Controllers\API\Auth;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Modules\Customer\Events\API\CustomerLoginEvent;
use Modules\Customer\Events\API\CustomerLogoutEvent;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AuthenticationControllerTest
 * @package Modules\Customer\Tests\Feature\Http\Controllers\API\Auth
 */
class AuthenticationControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer api controller
     */
    public function testFailRegisterEmptyPostData(): void
    {
        $data = [];

        $response = $this->post(route('api.register'), $data, [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'email',
                'password',
            ],
        ]);
    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer api controller
     */
    public function testFailRegisterWrongPasswordsValues(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'secretpassword',
            'password_confirmation' => 'secretpasword',
        ];

        $response = $this->post(route('api.register'), $data, [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password',
            ],
        ]);
    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer_api_controller
     */
    public function testSuccessRegister(): void
    {
        Event::fake();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'secretpassword',
            'password_confirmation' => 'secretpassword',
        ];

        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => config('app.name') . 'Personal Access Client'
        ]);

        $response = $this->post(route('api.register'), $data, [
            'Accept' => 'application/json',
        ]);

        Event::assertDispatched(CustomerLoginEvent::class);

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'time',
            'message',
            'data' => [
                'token',
                'token_type',
            ],
        ]);
    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer_api_controller
     */
    public function testFailLoginNonExistingEmail(): void
    {
        Event::fake();

        $response = $this->post(route('api.login'), [
            'email' => 'fake1@fake.com',
            'password' => 'fake',
        ], [
            'Accept' => 'application/json',
        ]);

        Event::assertNotDispatched(CustomerLoginEvent::class);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
                'password',
            ],
        ]);
    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer_api_controller
     */
    public function testFailLoginWrongPassword(): void
    {
        Event::fake();

        /** @var User $customer */
        $customer = factory(User::class)->create([
            'email' => 'fake@fake.com',
            'password' => bcrypt('secret_password'),
        ]);

        $response = $this->post(route('api.login'), [
            'email' => $customer->email,
            'password' => 'fake_password',
        ], [
            'Accept' => 'application/json',
        ]);

        Event::assertNotDispatched(CustomerLoginEvent::class);

        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'status',
            'time',
            'message',
        ]);
    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer_api_controller
     */
    public function testSuccessLogin(): void
    {
        Event::fake();

        /** @var User $customer */
        $customer = factory(User::class)->create([
            'password' => bcrypt('password'),
        ]);

        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => config('app.name') . 'Personal Access Client'
        ]);

        $response = $this->post(route('api.login'), [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        Event::assertDispatched(CustomerLoginEvent::class);

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'time',
            'message',
            'data' => [
                'token',
                'token_type',
            ]
        ]);
    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer_api_controller
     */
    public function testSuccessLogout(): void
    {
        Event::fake();
        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => config('app.name') . 'Personal Access Client'
        ]);

        /** @var User $customer */
        $customer = factory(User::class)->create();
        $this->actingAs($customer, 'api');

        $token = $customer->createToken('Test Token')->accessToken;
        $response = $this->post(route('api.logout'), [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ]);

        Event::assertDispatched(CustomerLogoutEvent::class);

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'time',
            'message',
        ]);

    }

    /**
     * @group customer
     * @group controller
     * @group api
     * @group auth
     * @group customer_api_controller
     */
    public function testMe(): void
    {
        /** @var User $customer */
        $customer = factory(User::class)->create();

        $this->actingAs($customer, 'api');

        $response = $this->get(route('api.me'), [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email',
            ],
        ]);

    }
}
