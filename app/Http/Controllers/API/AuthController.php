<?php

declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\DTO\CustomerDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Token;

/**
 * Class AuthController
 * @package App\Http\Controllers\API
 */
class AuthController extends Controller
{

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->getCredentials();

        if (!$token = JWTAuth::attempt($credentials)) {
            return (new ApiResponse())->unauthorized('Invalid credentials');
        }

        return (new ApiResponse())->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expire_in' => JWTFactory::getTTL() * 60,
        ]);
    }

    public function logout(): JsonResponse
    {
        try {
            $token = request()->bearerToken();

            auth()->invalidate($token);

            return (new ApiResponse())->success();
        } catch (JWTException $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }

    public function refresh()
    {
            $token = auth()->refresh();

            return (new ApiResponse())->success([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expire_in' => JWTFactory::getTTL() * 60,
            ]);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return (new ApiResponse())->success(new CustomerDTO($user));
    }
}
