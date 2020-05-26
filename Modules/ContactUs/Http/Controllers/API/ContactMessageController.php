<?php

declare(strict_types = 1);

namespace Modules\ContactUs\Http\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\ContactUs\Http\Requests\API\ContactMessageRequest;
use Modules\ContactUs\Jobs\NewMessageJob;
use Modules\ContactUs\Services\ContactMessageService;
use Modules\Core\Responses\ApiResponse;

class ContactMessageController extends Controller
{
    /**
     * @var ContactMessageService
     */
    private $messageService;

    /**
     * ContactMessageController constructor.
     * @param ContactMessageService $messageService
     */
    public function __construct(ContactMessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Store a newly created resource in storage.
     * @param ContactMessageRequest $request
     * @return JsonResponse
     */
    public function store(ContactMessageRequest $request): JsonResponse
    {
        try {
            $message = $this->messageService->storeData($request->getData());
        } catch (Exception $exception) {
            return (new ApiResponse())->exception();
        }

        NewMessageJob::dispatch($message)->onQueue('mail');

        return (new ApiResponse())->success();
    }
}
