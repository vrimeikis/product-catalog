<?php

declare(strict_types = 1);

namespace App\Listeners\API;

use App\Events\API\CustomerLoginEvent;
use App\Events\API\CustomerLogoutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class CustomerAuthLogSubscriber
 * @package App\Listeners\API
 */
class CustomerAuthLogSubscriber
{
    /**
     * @param Dispatcher $event
     */
    public function subscribe(Dispatcher $event): void
    {
        $event->listen(
            [
                CustomerLoginEvent::class,
                CustomerLogoutEvent::class,
            ],
            CustomerAuthLogListener::class
        );
    }
}