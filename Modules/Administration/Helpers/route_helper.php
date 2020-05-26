<?php

declare(strict_types = 1);

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;
use Modules\Administration\Entities\Admin;
use Modules\Administration\Services\RouteAccessManager;

/**
 * @param string $route
 * @return bool
 * @throws BindingResolutionException
 */
function canAccess(string $route): bool
{
    /** @var RouteAccessManager $manager */
    $manager = app()->make(RouteAccessManager::class);
    /** @var Admin $user */
    $user = Auth::guard('admin')->user();

    return $manager->accessAllowed($user, $route);
}

/**
 * @param array $routes
 * @return bool
 * @throws BindingResolutionException
 */
function canAccessAny(array $routes): bool
{
    /** @var RouteAccessManager $manager */
    $manager = app()->make(RouteAccessManager::class);
    /** @var Admin $user */
    $user = Auth::guard('admin')->user();

    foreach ($routes as $route) {
        if ($manager->accessAllowed($user, $route)) {
            return true;
        }
    }

    return false;
}