<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Profile Info --}}
            <div class="card p-6 sm:p-8 animate-fade-in">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <div class="shrink-0">
                        @if($user->profile && $user->profile->avatar)
                            <img src="{{ Storage::url($user->profile->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover ring-2 ring-brand-500/20">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-3xl font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-sm text-surface-500 mt-1 flex items-center justify-center sm:justify-start gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ __('На сайте с') }} {{ $user->created_at->format('d.m.Y') }}
                        </p>
                        @if($user->profile && $user->profile->bio)
                            <p class="mt-3 text-surface-300 leading-relaxed">{{ $user->profile->bio }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- User Articles --}}
            <div class="card p-6 sm:p-8">
                <h3 class="section-title flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    {{ __('Статьи') }}
                </h3>
                @forelse($user->articles as $article)
                    <div class="flex items-center justify-between gap-3 py-3.5 border-b border-surface-700/30 last:border-0">
                        <a href="{{ route('articles.show', $article->slug) }}" class="min-w-0 group">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-surface-300 group-hover:text-white font-medium transition-colors">{{ $article->title }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $article->status === 'draft' ? 'text-amber-300 border-amber-500/30 bg-amber-500/10' : 'text-emerald-300 border-emerald-500/30 bg-emerald-500/10' }}">
                                    {{ $article->status === 'draft' ? __('Черновик') : __('Опубликовано') }}
                                </span>
                            </div>
                            <span class="text-xs text-surface-500">{{ $article->created_at->format('d.m.Y') }} &middot; {{ $article->views_count }} {{ __('просм.') }}</span>
                        </a>

                        @if(Auth::id() === $user->id && $article->status === 'draft')
                            <form method="POST" action="{{ route('articles.publish', $article->slug) }}" class="shrink-0">
                                @csrf
                                <button type="submit" class="btn-ghost text-xs text-emerald-300 hover:text-emerald-200 hover:bg-emerald-500/10 border border-emerald-500/20">
                                    {{ __('Опубликовать') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-surface-500 text-sm">{{ __('Статей пока нет.') }}</p>
                @endforelse
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Subscribed Communities --}}
                <div class="card p-6 sm:p-8">
                    <h3 class="section-title flex items-center gap-2 mb-5">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ __('Сообщества') }}
                    </h3>
                    @forelse($user->subscribedCommunities as $community)
                        <a href="{{ route('communities.show', $community->slug) }}" class="flex items-center gap-3 py-3 border-b border-surface-700/30 last:border-0 group">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500/30 to-brand-500/30 flex items-center justify-center text-xs font-bold text-violet-300 shrink-0">
                                {{ strtoupper(substr($community->name, 0, 1)) }}
                            </div>
                            <span class="text-surface-300 group-hover:text-white font-medium transition-colors">{{ $community->name }}</span>
                        </a>
                    @empty
                        <p class="text-surface-500 text-sm">{{ __('Не подписан ни на одно сообщество.') }}</p>
                    @endforelse
                </div>

                {{-- Favorite Articles --}}
                <div class="card p-6 sm:p-8">
                    <h3 class="section-title flex items-center gap-2 mb-5">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        {{ __('Избранное') }}
                    </h3>
                    @forelse($user->favoriteArticles as $article)
                        <a href="{{ route('articles.show', $article->slug) }}" class="block py-3 border-b border-surface-700/30 last:border-0 text-surface-300 hover:text-white font-medium transition-colors">
                            {{ $article->title }}
                        </a>
                    @empty
                        <p class="text-surface-500 text-sm">{{ __('Нет избранных статей.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
