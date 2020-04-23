<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Supply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('supply.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Supply $supply
     * @return View
     */
    public function show(Supply $supply): View
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Supply $supply
     * @return View
     */
    public function edit(Supply $supply): View
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Supply $supply
     * @return RedirectResponse
     */
    public function update(Request $request, Supply $supply): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Supply $supply
     * @return RedirectResponse
     */
    public function destroy(Supply $supply): RedirectResponse
    {
        //
    }
}
