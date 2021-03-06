<?php

declare(strict_types = 1);

namespace Modules\Customer\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\Responses\ApiResponse;
use Modules\Customer\Http\Requests\API\CustomerUpdateRequest;
use Modules\Customer\Services\CustomerService;
use Throwable;

/**
 * Class CustomerController
 * @package Modules\Customer\Http\Controllers\API
 */
class CustomerController extends Controller
{
    /**
     * @var CustomerService
     */
    private CustomerService $customerService;

    /**
     * CustomerController constructor.
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Show the specified resource.
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        try {
            $customerDTO = $this->customerService->getMyInfoApi();

            return (new ApiResponse())->success($customerDTO);
        } catch (Throwable $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param CustomerUpdateRequest $request
     * @return JsonResponse
     */
    public function update(CustomerUpdateRequest $request): JsonResponse
    {
        try {
            $data = $request->getData();

            $this->customerService->updateMyInfoApi($data);
        } catch (Throwable $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }

        return (new ApiResponse())->success();
    }

    /**
     * Remove the specified resource from storage.
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        try {
            $this->customerService->deleteMe();
        } catch (Throwable $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }

        return (new ApiResponse())->success();
    }
}
