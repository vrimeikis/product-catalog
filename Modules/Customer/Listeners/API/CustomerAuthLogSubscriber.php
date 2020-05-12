<?php

declare(strict_types = 1);

namespace Modules\Customer\Listeners\API;

use Illuminate\Events\Dispatcher;
use Modules\Customer\Events\API\CustomerLoginEvent;
use Modules\Customer\Events\API\CustomerLogoutEvent;

/**
 * Class CustomerAuthLogSubscriber
 * @package Modules\Customer\Listeners\API
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