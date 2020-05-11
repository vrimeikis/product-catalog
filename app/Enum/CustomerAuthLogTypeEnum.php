<?php

declare(strict_types = 1);

namespace App\Enum;

use App\Enum\Abstracts\Enumerable;
use ReflectionException;

/**
 * Class CustomerAuthLogTypeEnum
 * @package App\Enum
 */
class CustomerAuthLogTypeEnum extends Enumerable
{
    /**
     * @return CustomerAuthLogTypeEnum
     * @throws ReflectionException
     */
    final public static function loggedIn(): CustomerAuthLogTypeEnum
    {
        return self::make('logged_in', __('Logged In'));
    }

    /**
     * @return CustomerAuthLogTypeEnum
     * @throws ReflectionException
     */
    final public static function loggedOut(): CustomerAuthLogTypeEnum
    {
        return self::make('logged_out', __('Logged Out'));
    }
}