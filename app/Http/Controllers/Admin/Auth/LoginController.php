<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Admin\Auth
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * LoginController constructor.
     */
    public function __construct() {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * @return View
     */
    public function showLoginForm(): View {
        return view('admin.auth.login');
    }

    /**
     * @return mixed
     */
    protected function guard() {
        return Auth::guard('admin');
    }

}
