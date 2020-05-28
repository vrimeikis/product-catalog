<?php

declare(strict_types = 1);

namespace Modules\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Api\Exceptions\AppKeyException;
use Modules\Api\Services\AppKeyManager;
use Modules\Core\Responses\ApiResponse;

/**
 * Class ApiPrivateMiddleware
 * @package Modules\Api\Http\Middleware
 */
class ApiPrivateMiddleware
{
    /**
     * @var AppKeyManager
     */
    private $appKeyManager;

    /**
     * ApiPrivateMiddleware constructor.
     * @param AppKeyManager $appKeyManager
     */
    public function __construct(AppKeyManager $appKeyManager)
    {
        $this->appKeyManager = $appKeyManager;
    }


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $devMode = (bool)config('api.dev_mode', false);

        if ($devMode) {
            return $next($request);
        }

        try {
            $this->appKeyManager->checkRequestApiKey();
        } catch (AppKeyException $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }

        return $next($request);
    }
}
