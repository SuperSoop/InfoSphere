<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\View\View;

class TagController extends Controller
{
    public function show(Tag $tag): View
    {
        $articles = $tag->articles()
            ->where('status', 'published')
            ->with(['user', 'category'])
            ->latest()
            ->paginate(12);

        return view('tags.show', compact('tag', 'articles'));
    }
}
