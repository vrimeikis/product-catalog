<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Roles;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class AdminController
 *
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $admins = Admin::query()->paginate();

        return view('admin.list', [
            'list' => $admins,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        /** @var Collection $roles */
        $roles = Roles::query()->orderBy('id')->get(['id', 'name']);

        return view('admin.form', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(AdminStoreRequest $request): RedirectResponse
    {
        try {
            $admin = Admin::query()->create($request->getData());
            $admin->roles()->sync($request->getRoles());
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', $exception->getMessage());
        }

        return redirect()->route('admins.index')
            ->with('status', 'Admin created!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Admin $admin
     *
     * @return View
     */
    public function edit(Admin $admin): View
    {
        /** @var Collection $roles */
        $roles = Roles::query()->orderBy('id')->get(['id', 'name']);
        $rolesIds = $admin->roles->pluck('id')->toArray();

        return view('admin.form', [
            'item' => $admin,
            'roles' => $roles,
            'rolesIds' => $rolesIds,
        ]);
    }

    /**
     * @return View
     */
    public function me(): View
    {
        /** @var Admin $admin */
        $admin = Auth::user();
        /** @var Collection $roles */
        $roles = Roles::query()->orderBy('id')->get(['id', 'name']);
        $rolesIds = $admin->roles->pluck('id')->toArray();

        return view('admin.form', [
            'item' => $admin,
            'roles' => $roles,
            'rolesIds' => $rolesIds,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateRequest $request
     * @param Admin $admin
     *
     * @return RedirectResponse
     */
    public function update(AdminUpdateRequest $request, Admin $admin): RedirectResponse
    {
        try {
            $admin->update($request->getData());
            $admin->roles()->sync($request->getRoles());
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Something wrong on try to update admin.');
        }

        return redirect()->route('admins.index')
            ->with('status', 'Admin Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Admin $admin
     *
     * @return RedirectResponse
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        try {
            $admin->delete();
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Something wrong on try to delete admin.');
        }

        return redirect()->route('admins.index')
            ->with('status', 'Admin deleted!');
    }

}
