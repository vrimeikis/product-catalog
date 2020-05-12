<?php

declare(strict_types = 1);

namespace Modules\Customer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Customer\Listeners\API\CustomerAuthLogSubscriber;

/**
 * Class EventServiceProvider
 * @package Modules\Customer\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    protected $subscribe = [
        CustomerAuthLogSubscriber::class,
    ];
}
