<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        $articles = $category->articles()
            ->where('status', 'published')
            ->with(['user', 'tags'])
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'articles'));
    }
}
