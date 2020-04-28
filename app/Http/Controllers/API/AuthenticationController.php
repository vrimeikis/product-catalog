<?php

declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\DTO\CustomerDTO;
use App\Events\API\CustomerLoginEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;

/**
 * Class AuthenticationController
 * @package App\Http\Controllers\API
 */
class AuthenticationController extends Controller
{
    /**
     * @var bool
     */
    private $loginAfterSignUp = true;

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            User::query()->create($request->getData());
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return (new ApiResponse())->success();
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!auth()->attempt($request->getCredentials())) {
                return (new ApiResponse())->unauthorized('Invalid credentials.');
            }

            /** @var User $customer */
            $customer = auth()->user();

            $personalAccessToken = $customer->createToken('Grant Client');

            event(new CustomerLoginEvent($customer, $personalAccessToken->token->id, Carbon::now()));

            return (new ApiResponse())->success([
                'token' => $personalAccessToken->accessToken,
                'token_type' => 'bearer',
            ]);

        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $value = $request->bearerToken();
            $tokenId = (new Parser())->parse($value)->getClaim('jti');

            /** @var User $customer */
            $customer = auth('api')->user();

            /** @var Token $token */
            $token = $customer->tokens->find($tokenId);
            $token->revoke();
        } catch (Exception $exception) {
            return (new  ApiResponse())->exception($exception->getMessage());
        }

        return (new ApiResponse())->success();
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            /** @var User $customer */
            $customer = auth('api')->user();

            $customerDTO = new CustomerDTO($customer);

            return (new ApiResponse())->success($customerDTO);
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }
}
