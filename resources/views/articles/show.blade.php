<x-app-layout>
    @section('meta')
        <meta name="description" content="{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 160) }}">
        <meta name="keywords" content="{{ $article->tags->pluck('name')->implode(', ') }}">
    @endsection

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="card overflow-hidden animate-fade-in">
                @if($article->image)
                    <div class="relative">
                        <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full h-72 sm:h-96 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-900/80 via-surface-900/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                            <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">{{ $article->title }}</h1>
                        </div>
                    </div>
                @endif

                <div class="p-6 sm:p-8">
                    @if(!$article->image)
                        <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight mb-4">{{ $article->title }}</h1>
                    @endif

                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-3 text-sm text-surface-400 mb-6">
                        <a href="{{ route('profile.show', $article->user) }}" class="flex items-center gap-2.5 group">
                            @if($article->user->profile && $article->user->profile->avatar)
                                <img src="{{ Storage::url($article->user->profile->avatar) }}" class="w-8 h-8 rounded-lg object-cover" alt="">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-xs font-bold text-white">
                                    {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="font-medium text-surface-300 group-hover:text-white transition-colors">{{ $article->user->name }}</span>
                        </a>
                        <span class="text-surface-600">&middot;</span>
                        <span>{{ $article->created_at->format('d.m.Y H:i') }}</span>
                        <span class="text-surface-600">&middot;</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ $article->views_count }}
                        </span>
                        @if($article->category)
                            <a href="{{ route('categories.show', $article->category->slug) }}" class="badge-brand">{{ $article->category->name }}</a>
                        @endif
                        @if($article->community)
                            <a href="{{ route('communities.show', $article->community->slug) }}" class="link text-xs">{{ $article->community->name }}</a>
                        @endif
                    </div>

                    {{-- Tags --}}
                    @if($article->tags->count())
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('tags.show', $tag->slug) }}" class="badge-surface hover:bg-surface-600/50 hover:text-surface-200">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="prose prose-invert prose-brand max-w-none mb-8 text-surface-300 leading-relaxed">
                        {!! Str::markdown($article->content, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                    </div>

                    {{-- Like & Favorite buttons --}}
                    @auth
                        <div class="flex items-center gap-3 border-t border-surface-700/30 pt-5 mb-8">
                            <button
                                id="like-btn"
                                onclick="toggleLike({{ $article->id }})"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Auth::user()->likedArticles->contains($article->id) ? 'bg-red-500/15 text-red-400 border border-red-500/20' : 'bg-surface-700/50 text-surface-400 border border-surface-600/30 hover:text-red-400 hover:bg-red-500/10 hover:border-red-500/20' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ Auth::user()->likedArticles->contains($article->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span id="like-count">{{ $article->likes->count() }}</span>
                            </button>

                            <button
                                id="favorite-btn"
                                onclick="toggleFavorite({{ $article->id }})"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Auth::user()->favoriteArticles->contains($article->id) ? 'bg-amber-500/15 text-amber-400 border border-amber-500/20' : 'bg-surface-700/50 text-surface-400 border border-surface-600/30 hover:text-amber-400 hover:bg-amber-500/10 hover:border-amber-500/20' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ Auth::user()->favoriteArticles->contains($article->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                {{ __('В избранное') }}
                            </button>

                            <div class="flex items-center gap-2 ml-auto">
                                @can('update', $article)
                                    <a href="{{ route('articles.edit', $article->slug) }}" class="btn-ghost text-xs gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ __('Редактировать') }}
                                    </a>
                                @endcan
                                @can('delete', $article)
                                    <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Вы уверены?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost text-xs text-red-400 hover:text-red-300 hover:bg-red-500/10">{{ __('Удалить') }}</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endauth

                    {{-- Comments Section --}}
                    <div class="border-t border-surface-700/30 pt-8">
                        <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ __('Комментарии') }} <span class="text-surface-500 font-normal">({{ $article->comments->count() }})</span>
                        </h3>

                        @auth
                            <form action="{{ route('comments.store') }}" method="POST" class="mb-8">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                <textarea name="body" rows="3" required placeholder="{{ __('Написать комментарий...') }}" class="input-field resize-none"></textarea>
                                <x-primary-button class="mt-3">{{ __('Отправить') }}</x-primary-button>
                            </form>
                        @endauth

                        <div class="space-y-1">
                            @foreach($article->comments as $comment)
                                @include('comments._comment', ['comment' => $comment])
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
@push('scripts')
<script>
    function toggleLike(articleId) {
        axios.post(`/articles/${articleId}/like`)
            .then(response => {
                const btn = document.getElementById('like-btn');
                const count = document.getElementById('like-count');
                const svg = btn.querySelector('svg');
                count.textContent = response.data.count;
                if (response.data.liked) {
                    btn.className = 'flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 bg-red-500/15 text-red-400 border border-red-500/20';
                    svg.setAttribute('fill', 'currentColor');
                } else {
                    btn.className = 'flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 bg-surface-700/50 text-surface-400 border border-surface-600/30 hover:text-red-400 hover:bg-red-500/10 hover:border-red-500/20';
                    svg.setAttribute('fill', 'none');
                }
            });
    }

    function toggleFavorite(articleId) {
        axios.post(`/articles/${articleId}/favorite`)
            .then(response => {
                const btn = document.getElementById('favorite-btn');
                const svg = btn.querySelector('svg');
                if (response.data.favorited) {
                    btn.className = 'flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 bg-amber-500/15 text-amber-400 border border-amber-500/20';
                    svg.setAttribute('fill', 'currentColor');
                } else {
                    btn.className = 'flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 bg-surface-700/50 text-surface-400 border border-surface-600/30 hover:text-amber-400 hover:bg-amber-500/10 hover:border-amber-500/20';
                    svg.setAttribute('fill', 'none');
                }
            });
    }
</script>
@endpush
</x-app-layout>
