<?php

declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\DTO\CustomerMiniDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Class AuthenticateController
 * @package App\Http\Controllers\API
 */
class AuthenticateController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!auth()->attempt($request->getCredentials())) {
                return (new ApiResponse())->unauthorized('Invalid credentials');
            }

            /** @var User $customer */
            $customer = auth('sanctum')->user();
            $token = $customer->createToken($request->getDeviceName())->plainTextToken;

            return (new ApiResponse())->success([
                'token' => $token,
                'token_type' => 'bearer',
            ]);
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            /** @var User $customer */
            $customer = auth('sanctum')->user();

            return (new ApiResponse())->success(new CustomerMiniDTO($customer));
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            /** @var User $customer */
            $customer = auth('sanctum')->user();

            /** @var PersonalAccessToken $token */
            $token = $customer->currentAccessToken();
            $token->delete();

            return (new ApiResponse())->success();
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }
}
