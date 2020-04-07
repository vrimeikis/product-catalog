<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleStoreRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Roles;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $roles = Roles::query()->paginate();

        return view('role.index', [
            'list' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('role.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleStoreRequest $request
     * @return RedirectResponse
     */
    public function store(RoleStoreRequest $request): RedirectResponse
    {
        try {
            Roles::query()->create($request->getData());
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', $exception->getMessage());
        }

        return redirect()->route('roles.index')
            ->with('status', 'Role created.');
    }

    /**
     * Display the specified resource.
     *
     * @param Roles $role
     * @return View
     */
    public function show(Roles $role): View
    {
        return view('role.view', [
            'item' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Roles $role
     *
     * @return View
     */
    public function edit(Roles $role): View
    {
        return view('role.form', ['item' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param Roles $role
     * @return RedirectResponse
     */
    public function update(RoleUpdateRequest $request, Roles $role): RedirectResponse
    {
        try {
            $role->update($request->getData());
        } catch (Exception $exception) {
            return back()->withInput()
                ->with('danger', $exception->getMessage());
        }

        return redirect()->route('roles.index')
            ->with('status', 'Role updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Roles $role
     * @return RedirectResponse
     */
    public function destroy(Roles $role): RedirectResponse
    {
        try {
            $role->delete();
        } catch (Exception $exception) {
            return back()
                ->with('danger', $exception->getMessage());
        }

        return redirect()->route('roles.index')
            ->with('status', 'Role deleted.');
    }
}
