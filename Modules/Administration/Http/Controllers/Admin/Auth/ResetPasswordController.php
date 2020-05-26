<?php

declare(strict_types = 1);

namespace Modules\Administration\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * Class ResetPasswordController
 * @package Modules\Administration\Http\Controllers\Admin\Auth
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @param Request $request
     * @param null $token
     *
     * @return View
     */
    public function showResetForm(Request $request, $token = null): View
    {
        return view('administration::admin.auth.passwords.reset', [
            'token' => $token,
            'email' => $request->input('email'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only(
                'email', 'password', 'password_confirmation', 'token'
            ), ['active' => true]
        );
    }

    /**
     * @return PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return Password::broker('admins');
    }

}
