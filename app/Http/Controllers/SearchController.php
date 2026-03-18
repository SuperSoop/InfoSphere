<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');
        $categoryId = $request->input('category');
        $tagId = $request->input('tag');
        $sort = $request->input('sort', 'latest');

        $articles = Article::where('status', 'published')
            ->with(['user', 'category', 'tags']);

        if ($query) {
            $articles->where('title', 'LIKE', "%{$query}%");
        }

        if ($categoryId) {
            $articles->where('category_id', $categoryId);
        }

        if ($tagId) {
            $articles->whereHas('tags', fn($q) => $q->where('tags.id', $tagId));
        }

        $articles = match ($sort) {
            'popular' => $articles->orderByDesc('views_count'),
            'oldest' => $articles->oldest(),
            default => $articles->latest(),
        };

        $articles = $articles->paginate(12)->appends($request->query());

        $categories = Category::all();
        $tags = Tag::all();

        return view('search.index', compact('articles', 'categories', 'tags', 'query', 'categoryId', 'tagId', 'sort'));
    }
}
