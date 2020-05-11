<?php

declare(strict_types = 1);

namespace Modules\Customer\Exceptions;

use Exception;
use Illuminate\Http\Response;

/**
 * Class CustomerException
 * @package Modules\Customer\Exceptions
 */
class CustomerException extends Exception
{
    /**
     * @return CustomerException
     */
    public static function noCustomer(): CustomerException
    {
        return new static('Your data not found.',Response::HTTP_NOT_FOUND);
    }
}