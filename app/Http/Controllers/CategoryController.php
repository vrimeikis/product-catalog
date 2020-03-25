<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        /** @var LengthAwarePaginator $categories */
        $categories = Category::query()->paginate();

        return view('category.list', ['list' => $categories]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('category.create');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $data = $request->only(
            'title'
        );

        Category::query()->create($data);

        return redirect()->route('categories.index');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id) {
        // SELECT * FROM products WHERE id = ?
        $category = Category::query()->find($id);

        return view('category.edit', ['category' => $category]);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id) {
        $data = $request->only('title');

        Category::query()
            ->where('id', '=', $id)
            ->update($data);

        return redirect()->route('categories.index');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id) {
        // DELETE FROM products WHERE id = ?
        Category::query()
            ->where('id', '=', $id)
            ->delete();

        return redirect()->route('categories.index');
    }

}
