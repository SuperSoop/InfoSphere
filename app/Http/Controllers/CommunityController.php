<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        $communities = Community::withCount('subscribers')
            ->with('creator')
            ->latest()
            ->paginate(12);

        return view('communities.index', compact('communities'));
    }

    public function show(Community $community): View
    {
        $community->load('creator');
        $community->loadCount('subscribers');

        $articles = $community->articles()
            ->where('status', 'published')
            ->with(['user', 'category', 'tags'])
            ->latest()
            ->paginate(10);

        $isSubscribed = Auth::check() && $community->subscribers()->where('user_id', Auth::id())->exists();

        return view('communities.show', compact('community', 'articles', 'isSubscribed'));
    }

    public function create(): View
    {
        return view('communities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'is_private' => ['boolean'],
        ]);

        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $data['is_private'] = $request->boolean('is_private');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('communities', 'public');
        }

        $community = Community::create($data);

        return redirect()->route('communities.show', $community->slug)
            ->with('success', __('Сообщество успешно создано.'));
    }

    public function edit(Community $community): View
    {
        $this->authorize('update', $community);

        return view('communities.edit', compact('community'));
    }

    public function update(Request $request, Community $community): RedirectResponse
    {
        $this->authorize('update', $community);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'is_private' => ['boolean'],
        ]);

        $data['is_private'] = $request->boolean('is_private');

        if ($request->hasFile('cover_image')) {
            if ($community->cover_image) {
                Storage::disk('public')->delete($community->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('communities', 'public');
        }

        $community->update($data);

        return redirect()->route('communities.show', $community->slug)
            ->with('success', __('Сообщество успешно обновлено.'));
    }

    public function destroy(Community $community): RedirectResponse
    {
        $this->authorize('delete', $community);

        if ($community->cover_image) {
            Storage::disk('public')->delete($community->cover_image);
        }

        $community->delete();

        return redirect()->route('communities.index')
            ->with('success', __('Сообщество успешно удалено.'));
    }

    public function subscribe(Community $community)
    {
        $user = Auth::user();
        $exists = $community->subscribers()->where('user_id', $user->id)->exists();

        if ($exists) {
            $community->subscribers()->detach($user->id);
        } else {
            $community->subscribers()->attach($user->id, ['created_at' => now()]);
        }

        $count = $community->subscribers()->count();

        return response()->json([
            'subscribed' => !$exists,
            'count' => $count,
        ]);
    }
}
