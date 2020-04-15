<?php

declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Category;
use App\DTO\Abstracts\CollectionDTO;
use App\DTO\CategoryDTO;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categoryDTO = new CollectionDTO();

        $data = Category::query()->get();

        foreach ($data as $item) {
            $categoryDTO->pushItem(new CategoryDTO($item));
        }

        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'message' => '',
            'data' => $categoryDTO,
            ]);
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
            $category = Category::query()->where('slug', '=', $slug)
                ->firstOrFail();

            return response()->json([
                'code' => JsonResponse::HTTP_OK,
                'message' => '',
                'data' => new CategoryDTO($category)
            ]);
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                'code' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'No result.'
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            logger()->error($exception->getMessage());

            return response()->json(['message' => 'Something wrong'], JsonResponse::HTTP_BAD_REQUEST);
        }

    }
}
