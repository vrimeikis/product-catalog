<?php

declare(strict_types = 1);

use App\Admin;
use App\Services\RouteAccessManager;
use Illuminate\Support\Facades\Auth;

function canAccess(string $route): bool
{
    /** @var RouteAccessManager $manager */
    $manager = app()->make(RouteAccessManager::class);
    /** @var Admin $user */
    $user = Auth::guard('admin')->user();

    return $manager->accessAllowed($user, $route);
}

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