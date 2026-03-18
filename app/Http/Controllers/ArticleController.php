<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Community;
use App\Models\Tag;
use App\Notifications\ArticleLiked;
use App\Notifications\NewPostInCommunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::where('status', 'published')
            ->with(['user.profile', 'category', 'tags'])
            ->latest()
            ->paginate(12);

        $popular = Article::where('status', 'published')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $newCommunities = Community::latest()->take(5)->get();

        return view('articles.index', compact('articles', 'popular', 'newCommunities'));
    }

    public function show(Article $article): View
    {
        if ($article->status === 'draft' && (!Auth::check() || (Auth::id() !== $article->user_id && !Auth::user()->isAdmin()))) {
            abort(403);
        }

        $article->increment('views_count');
        $article->load(['user.profile', 'category', 'tags', 'comments' => function ($q) {
            $q->whereNull('parent_id')->with(['user.profile', 'replies.user.profile'])->latest();
        }]);

        return view('articles.show', compact('article'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $tags = Tag::all();
        $communities = Community::where('user_id', Auth::id())
            ->orWhereHas('subscribers', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        return view('articles.create', compact('categories', 'tags', 'communities'));
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $article = Article::create($data);
        $article->tags()->sync($tags);

        // Notify community subscribers
        if ($article->community_id && $article->status === 'published') {
            $community = $article->community;
            $subscribers = $community->subscribers()->where('user_id', '!=', Auth::id())->get();
            foreach ($subscribers as $subscriber) {
                $subscriber->notify(new NewPostInCommunity($article, $community));
            }
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', __('Статья успешно создана.'));
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        $categories = Category::all();
        $tags = Tag::all();
        $communities = Community::where('user_id', Auth::id())
            ->orWhereHas('subscribers', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        return view('articles.edit', compact('article', 'categories', 'tags', 'communities'));
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $article->update($data);
        $article->tags()->sync($tags);

        return redirect()->route('articles.show', $article->slug)
            ->with('success', __('Статья успешно обновлена.'));
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', __('Статья успешно удалена.'));
    }

    public function publish(Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        if ($article->status === 'published') {
            return back()->with('success', __('Статья уже опубликована.'));
        }

        $article->update(['status' => 'published']);

        if ($article->community_id) {
            $community = $article->community;
            $subscribers = $community->subscribers()->where('user_id', '!=', Auth::id())->get();

            foreach ($subscribers as $subscriber) {
                $subscriber->notify(new NewPostInCommunity($article, $community));
            }
        }

        return redirect()->route('articles.show', $article->slug)
            ->with('success', __('Черновик опубликован.'));
    }

    public function like(Article $article)
    {
        $user = Auth::user();
        $user->likedArticles()->toggle($article->id);
        $liked = $user->likedArticles()->where('article_id', $article->id)->exists();
        $count = $article->likes()->count();

        // Notify article author
        if ($liked && $article->user_id !== $user->id) {
            $article->user->notify(new ArticleLiked($user, $article));
        }

        return response()->json([
            'liked' => $liked,
            'count' => $count,
        ]);
    }

    public function favorite(Article $article)
    {
        $user = Auth::user();
        $user->favoriteArticles()->toggle($article->id);

        return response()->json([
            'favorited' => $user->favoriteArticles()->where('article_id', $article->id)->exists(),
        ]);
    }
}
