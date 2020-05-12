<?php

declare(strict_types = 1);

namespace Modules\Customer\Listeners\API;


use Modules\Customer\Events\API\Contracts\CustomerAuthContract;

/**
 * Class CustomerAuthLogListener
 * @package Modules\Customer\Listeners\API
 */
class CustomerAuthLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     *
     * @param CustomerAuthContract $event
     * @return void
     */
    public function handle(CustomerAuthContract $event): void
    {
        $event->customer->authLogs()->create([
            'token_id' => $event->tokenId,
            'event_time' => $event->eventTime,
            'type' => $event->getType(),
        ]);
    }
}
