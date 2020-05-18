<?php

namespace Modules\Customer\Tests\Feature\Http\Controllers\API\Auth;

use App\Http\Responses\ApiResponse;
use App\User;
use Illuminate\Http\JsonResponse;
use Modules\Customer\Http\Controllers\API\Auth\AuthenticationController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'secretpassword',
            'password_confirmation' => 'secretpassword',
        ];

        $this->partialMock(AuthenticationController::class, function ($mock) {
            $mock->shouldReceive('login')->once()
                ->andReturn((new ApiResponse())->success([
                    'token' => 'some string',
                    'token_type' => 'bearer',
                ]));
        });

        $response = $this->post(route('api.register'), $data, [
            'Accept' => 'application/json',
        ]);

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
     * @group auth1
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
