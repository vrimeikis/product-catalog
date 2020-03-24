<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        /** @var LengthAwarePaginator $categories */
        $categories = Category::query()->paginate();

        return view('category.list', ['list' => $categories]);
    }

}
