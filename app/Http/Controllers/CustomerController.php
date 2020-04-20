<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $users = User::query()->paginate();

        return view('customer.list', ['list' => $users]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('customer.form');
    }

    /**
     * @param CustomerStoreRequest $request
     * @return RedirectResponse
     */
    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        try {
            User::query()->create($request->getData());

            return redirect()->route('customers.index')
                ->with('status', 'Customer created.');
        } catch (Exception $exception) {
            return redirect()->back()->withInput()
                ->with('danger', $exception->getMessage());
        }
    }

    /**
     * @param User $customer
     * @return View
     */
    public function edit(User $customer): View
    {
        return view('customer.form', ['customer' => $customer]);
    }

    /**
     * @param CustomerUpdateRequest $request
     * @param User $customer
     * @return RedirectResponse
     */
    public function update(CustomerUpdateRequest $request, User $customer): RedirectResponse
    {
        try {
            $customer->name = $request->getName();
            $customer->email = $request->getEmail();

            $password = $request->getHashPassword();
            if (!empty($password)) {
                $customer->password = $password;
            }

            $customer->save();

            return redirect()->route('customers.index')
                ->with('status', 'Customer updated!');
        } catch (Exception $exception) {
            return redirect()->back()->withInput()
                ->with('danger', $exception->getMessage());
        }
    }

}
