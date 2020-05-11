<?php

declare(strict_types = 1);

namespace App\Events\API;

use Modules\Customer\Enum\CustomerAuthLogTypeEnum;
use App\Events\API\Abstracts\CustomerAuthAbstract;
use ReflectionException;

/**
 * Class CustomerLogoutEvent
 * @package App\Events\API
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
