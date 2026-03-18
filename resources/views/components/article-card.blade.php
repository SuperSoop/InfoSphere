@props(['article'])

<article class="card group overflow-hidden animate-fade-in">
    @if($article->image)
        <a href="{{ route('articles.show', $article->slug) }}" class="block relative overflow-hidden">
            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-surface-900/60 via-transparent to-transparent"></div>
        </a>
    @else
        <a href="{{ route('articles.show', $article->slug) }}" class="block h-32 bg-gradient-to-br from-brand-500/20 via-surface-800 to-violet-500/20 relative">
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-10 h-10 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
        </a>
    @endif
    <div class="p-5">
        <div class="flex items-center gap-2 mb-3">
            @if($article->category)
                <a href="{{ route('categories.show', $article->category->slug) }}" class="badge-brand text-[11px]">{{ $article->category->name }}</a>
            @endif
            <span class="text-[11px] text-surface-500">{{ $article->created_at->format('d.m.Y') }}</span>
            <span class="text-surface-700">&middot;</span>
            <span class="text-[11px] text-surface-500 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                {{ $article->views_count }}
            </span>
        </div>
        <h3 class="text-base font-semibold mb-2 leading-snug">
            <a href="{{ route('articles.show', $article->slug) }}" class="text-white hover:text-brand-300 transition-colors duration-200">
                {{ $article->title }}
            </a>
        </h3>
        @if($article->excerpt)
            <p class="text-surface-400 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $article->excerpt }}</p>
        @endif
        <div class="flex items-center justify-between pt-3 border-t border-surface-700/30">
            <a href="{{ route('profile.show', $article->user) }}" class="flex items-center gap-2 group/author">
                @if($article->user->profile && $article->user->profile->avatar)
                    <img src="{{ Storage::url($article->user->profile->avatar) }}" alt="{{ $article->user->name }}" class="w-6 h-6 rounded-md object-cover ring-1 ring-surface-600/50">
                @else
                    <div class="w-6 h-6 rounded-md bg-gradient-to-br from-brand-400/80 to-brand-600/80 flex items-center justify-center text-[10px] font-bold text-white">
                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                    </div>
                @endif
                <span class="text-xs text-surface-400 group-hover/author:text-surface-200 transition-colors">{{ $article->user->name }}</span>
            </a>
            <div class="flex gap-2">
                @if($article->tags->count())
                    @foreach($article->tags->take(2) as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" class="text-[11px] text-surface-500 hover:text-brand-400 transition-colors">#{{ $tag->name }}</a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</article>
