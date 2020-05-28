<?php

declare(strict_types = 1);

namespace Modules\Api\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Api\Entities\ApiKey;
use Modules\Api\Http\Requests\Admin\AppKeyStoreRequest;
use Modules\Api\Http\Requests\Admin\AppKeyUpdateRequest;
use Modules\Api\Services\ApiKeyService;

/**
 * Class ApiController
 * @package Modules\Api\Http\Controllers\Admin
 */
class ApiController extends Controller
{
    /**
     * @var ApiKeyService
     */
    private $apiKeyService;

    /**
     * ApiController constructor.
     * @param ApiKeyService $apiKeyService
     */
    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }

    /**
     * Display a listing of the resource.
     * @return View
     */
    public function index(): View
    {
        $apiKeys = $this->apiKeyService->getPaginate();

        return view('api::api_keys.list', ['list' => $apiKeys]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        return view('api::api_keys.form');
    }

    /**
     * Store a newly created resource in storage.
     * @param AppKeyStoreRequest $request
     * @return RedirectResponse
     */
    public function store(AppKeyStoreRequest $request): RedirectResponse
    {
        try {
            $this->apiKeyService->createNew($request->getTitle(), $request->getActive());

            return redirect()->route('api_keys.index')
                ->with('status', 'App key created');
        } catch (Exception $exception) {
            return back()->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @param ApiKey $apiKey
     * @return View
     */
    public function show(ApiKey $apiKey): View
    {
        return view('api::api_keys.show', ['item' => $apiKey]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param ApiKey $apiKey
     * @return View
     */
    public function edit(ApiKey $apiKey): View
    {
        return view('api::api_keys.form', ['item' => $apiKey]);
    }

    /**
     * Update the specified resource in storage.
     * @param AppKeyUpdateRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(AppKeyUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $this->apiKeyService->updateById($id, $request->getTitle(), $request->getActive());

            return redirect()->route('api_keys.index')
                ->with('status', 'App key updated.');
        } catch (Exception $exception) {
            return back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ApiKey $apiKey
     * @return RedirectResponse
     */
    public function destroy(ApiKey $apiKey): RedirectResponse
    {
        try {
            $apiKey->delete();

            return redirect()->route('api_keys.index')
                ->with('status', 'Api key deleted.');
        } catch (Exception $exception) {
            return back()->with('danger', $exception->getMessage());
        }
    }
}
