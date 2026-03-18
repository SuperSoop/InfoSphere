<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Title -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ __('Статьи') }}</h1>
                    <p class="text-surface-400 text-sm mt-1">{{ __('Последние публикации и новости') }}</p>
                </div>
                @auth
                    <a href="{{ route('articles.create') }}" class="btn-primary gap-2 hidden sm:inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Написать статью') }}
                    </a>
                @endauth
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Main Feed --}}
                <div class="flex-1 min-w-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($articles as $article)
                            <x-article-card :article="$article" />
                        @empty
                            <div class="col-span-2 card p-12 text-center">
                                <svg class="w-16 h-16 text-surface-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <p class="text-surface-400 text-lg font-medium">{{ __('Статей пока нет.') }}</p>
                                <p class="text-surface-500 text-sm mt-1">{{ __('Будьте первым, кто напишет статью!') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $articles->links() }}
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="w-full lg:w-80 shrink-0 space-y-6">
                    {{-- Popular Articles --}}
                    <div class="card p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <h3 class="font-semibold text-white">{{ __('Популярные статьи') }}</h3>
                        </div>
                        @foreach($popular as $index => $item)
                            <a href="{{ route('articles.show', $item->slug) }}" class="flex items-start gap-3 py-3 border-b border-surface-700/30 last:border-0 group">
                                <span class="shrink-0 w-6 h-6 rounded-lg bg-surface-700/50 flex items-center justify-center text-xs font-bold text-surface-400 group-hover:bg-brand-500/20 group-hover:text-brand-300 transition-colors">{{ $index + 1 }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm text-surface-300 group-hover:text-white transition-colors line-clamp-2 leading-snug">{{ $item->title }}</p>
                                    <p class="text-[11px] text-surface-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ $item->views_count }} {{ __('просмотров') }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- New Communities --}}
                    <div class="card p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <h3 class="font-semibold text-white">{{ __('Новые сообщества') }}</h3>
                        </div>
                        @foreach($newCommunities as $community)
                            <a href="{{ route('communities.show', $community->slug) }}" class="flex items-center gap-3 py-3 border-b border-surface-700/30 last:border-0 group">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500/30 to-brand-500/30 flex items-center justify-center text-xs font-bold text-violet-300 shrink-0">
                                    {{ strtoupper(substr($community->name, 0, 1)) }}
                                </div>
                                <span class="text-sm text-surface-300 group-hover:text-white transition-colors truncate">{{ $community->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
