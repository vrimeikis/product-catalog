<?php

declare(strict_types = 1);

namespace Modules\Api\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class AppKeyException
 * @package Modules\Api\Exceptions
 */
class AppKeyException extends Exception
{
    /**
     * @return AppKeyException
     */
    public static function missingAppKey(): AppKeyException
    {
        return new static('Missing Api key.', JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @return AppKeyException
     */
    public static function keyExpired(): AppKeyException
    {
        return new static('App key expired.', JsonResponse::HTTP_BAD_REQUEST);
    }

}