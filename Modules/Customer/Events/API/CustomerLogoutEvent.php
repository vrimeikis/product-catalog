<?php

declare(strict_types = 1);

namespace Modules\Customer\Events\API;

use Modules\Customer\Enum\CustomerAuthLogTypeEnum;
use Modules\Customer\Events\API\Abstracts\CustomerAuthAbstract;
use ReflectionException;

/**
 * Class CustomerLogoutEvent
 * @package Modules\Customer\Events\API
 */
class CustomerLogoutEvent extends CustomerAuthAbstract
{

    /**
     * @return string
     * @throws ReflectionException
     */
    public function getType(): string
    {
        return CustomerAuthLogTypeEnum::loggedOut()->id();
    }
}
