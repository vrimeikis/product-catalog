<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @param UserUpdateRequest $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse {
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $password = $request->input('password');
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }

        $user->save();

        return redirect()->route('users.me')
            ->with('status', 'Info updated successfully!');
    }

}
