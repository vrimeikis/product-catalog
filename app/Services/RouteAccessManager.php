<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Middleware\RouteAccessMiddleware;
use App\Roles;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class RouteAccessManager
{
    public function getRoutes(): array
    {
        $routes = collect(Route::getRoutes());

        return $routes->filter(function (\Illuminate\Routing\Route $route) {
            return in_array(RouteAccessMiddleware::ALIAS, $route->gatherMiddleware());
        })->map(function (\Illuminate\Routing\Route $route) {
            return $route->getName();
        })->toArray();
    }

    public function accessAllowed(Authenticatable $user, string $route): bool
    {
        if (!Route::has($route)) {
            return false;
        }

        /** @var Collection|Roles[] $roles */
        $roles = $user->roles()->get();

        if ($roles->contains('full_access', '=', true)) {
            return true;
        }

        return $roles->flatMap(function (Roles $role) {
            return $role->accessible_routes;
        })->contains($route);
    }
}