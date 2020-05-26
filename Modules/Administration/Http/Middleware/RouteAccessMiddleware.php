<?php

declare(strict_types = 1);

namespace Modules\Administration\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Administration\Services\RouteAccessManager;

class RouteAccessMiddleware
{
    const ALIAS = 'admin-access';

    const ACCESS_NOT_ALLOWED_MESSAGE = 'You don\'t have access to requested page';

    /**
     * @var RouteAccessManager
     */
    private $routeAccessManager;

    /**
     * RouteAccessMiddleware constructor.
     * @param RouteAccessManager $routeAccessManager
     */
    public function __construct(RouteAccessManager $routeAccessManager)
    {
        $this->routeAccessManager = $routeAccessManager;
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
        if ($this->shouldBlockAccess()) {
            return redirect()->route('index')
                ->with('danger', self::ACCESS_NOT_ALLOWED_MESSAGE);
        }

        return $next($request);
    }

    /**
     * @return bool
     */
    private function shouldBlockAccess(): bool
    {
        return Auth::guard('admin')->check() &&
            !$this->routeAccessManager->accessAllowed(
                Auth::guard('admin')->user(),
                (string)Arr::get(Route::current()->action, 'as')
            );
    }
}
