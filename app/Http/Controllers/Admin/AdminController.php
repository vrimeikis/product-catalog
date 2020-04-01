<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View {
        $admins = Admin::query()->paginate();

        return view('admin.list', [
            'list' => $admins
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View {
        return view('admin.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(AdminStoreRequest $request): RedirectResponse {
        try {
            Admin::query()->create($request->getData());
        } catch (Exception $exception) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Something wrong on try to create admin.');
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
    public function edit(Admin $admin): View {
        return view('admin.form', ['item' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Admin $admin
     *
     * @return Response
     */
    public function update(Request $request, Admin $admin) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Admin $admin
     *
     * @return Response
     */
    public function destroy(Admin $admin) {
        //
    }

}
