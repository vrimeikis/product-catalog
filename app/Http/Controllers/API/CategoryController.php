<?php

declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CategoryController constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categoryDTO = $this->categoryService->getAllForApi();

            return response()->json([
                'code' => JsonResponse::HTTP_OK,
                'message' => '',
                'data' => $categoryDTO,
            ]);
        } catch (Throwable $exception) {
            logger()->error($exception->getMessage());

            return response()->json(['message' => 'Something wrong'], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $categoryDTO = $this->categoryService->getBySlugForApi($slug);

            return response()->json([
                'code' => JsonResponse::HTTP_OK,
                'message' => '',
                'data' => $categoryDTO,
            ]);
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                'code' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'No result.',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            logger()->error($exception->getMessage());

            return response()->json(['message' => 'Something wrong'], JsonResponse::HTTP_BAD_REQUEST);
        }

    }
}
