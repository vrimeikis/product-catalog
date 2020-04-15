<?php

declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
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
        $data = Category::query()->get();

        return response()->json(new CategoryResourceCollection($data));
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

            return response()->json(new CategoryResource($category));
        } catch (ModelNotFoundException $exception) {

            return response()->json(['message' => 'No result.'], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            logger()->error($exception->getMessage());

            return response()->json(['message' => 'Something wrong'], JsonResponse::HTTP_BAD_REQUEST);
        }

    }
}
