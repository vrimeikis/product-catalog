<?php

declare(strict_types = 1);

namespace Modules\Customer\Http\Controllers\API;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Customer\Http\Requests\API\CustomerUpdateRequest;
use Modules\Customer\Services\CustomerService;
use Throwable;

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
            $this->customerService->updateMyInfoApi($request->getData());
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
