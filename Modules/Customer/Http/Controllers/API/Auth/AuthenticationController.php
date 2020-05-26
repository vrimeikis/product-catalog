<?php

declare(strict_types = 1);

namespace Modules\Customer\Http\Controllers\API\Auth;

use Modules\Core\Responses\ApiResponse;
use Modules\Customer\DTO\CustomerDTO;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;
use Modules\Customer\Events\API\CustomerLoginEvent;
use Modules\Customer\Events\API\CustomerLogoutEvent;
use Modules\Customer\Http\Requests\API\LoginRequest;
use Modules\Customer\Http\Requests\API\RegisterRequest;
use Modules\Customer\Services\CustomerService;

/**
 * Class AuthenticationController
 * @package Modules\Customer\Http\Controllers\API\Auth
 */
class AuthenticationController extends Controller
{

    /**
     * @var bool
     */
    private $loginAfterSignUp = true;
    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * AuthenticationController constructor.
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $this->customerService->create($request->getData());
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

            event(new CustomerLogoutEvent($customer, $tokenId, Carbon::now()));

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
