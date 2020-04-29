<?php

declare(strict_types = 1);

namespace App\Events\API;

use App\Enum\CustomerAuthLogTypeEnum;
use App\Events\API\Abstracts\CustomerAuthAbstract;

/**
 * Class CustomerLoginEvent
 * @package App\Events\API
 */
class CustomerLoginEvent extends CustomerAuthAbstract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CustomerAuthLogTypeEnum::loggedIn()->id();
    }
}
