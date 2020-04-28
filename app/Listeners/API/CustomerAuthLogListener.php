<?php

declare(strict_types = 1);

namespace App\Listeners\API;

use App\Events\API\Contracts\CustomerAuthContract;
use App\Events\API\CustomerLoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CustomerAuthLogListener
 * @package App\Listeners\API
 */
class CustomerAuthLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
