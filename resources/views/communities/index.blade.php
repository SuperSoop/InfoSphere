<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ __('Сообщества') }}</h1>
                    <p class="text-surface-400 text-sm mt-1">{{ __('Находите единомышленников и обсуждайте интересные темы') }}</p>
                </div>
                @auth
                    <a href="{{ route('communities.create') }}" class="btn-primary gap-2 hidden sm:inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Создать') }}
                    </a>
                @endauth
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($communities as $community)
                    <div class="card group overflow-hidden animate-fade-in">
                        @if($community->cover_image)
                            <a href="{{ route('communities.show', $community->slug) }}" class="block relative overflow-hidden">
                                <img src="{{ Storage::url($community->cover_image) }}" alt="{{ $community->name }}" class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-surface-900/60 via-transparent to-transparent"></div>
                            </a>
                        @else
                            <a href="{{ route('communities.show', $community->slug) }}" class="block h-28 bg-gradient-to-br from-violet-500/20 via-surface-800 to-brand-500/20 relative">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </a>
                        @endif
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="text-base font-semibold leading-snug">
                                    <a href="{{ route('communities.show', $community->slug) }}" class="text-white hover:text-brand-300 transition-colors duration-200">
                                        {{ $community->name }}
                                    </a>
                                </h3>
                                @if($community->is_private)
                                    <span class="badge-warning text-[10px] shrink-0">
                                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        {{ __('Приват') }}
                                    </span>
                                @endif
                            </div>
                            @if($community->description)
                                <p class="text-surface-400 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $community->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-surface-500 pt-3 border-t border-surface-700/30">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    {{ $community->subscribers_count ?? $community->subscribers->count() }} {{ __('подписчиков') }}
                                </span>
                                <a href="{{ route('profile.show', $community->creator) }}" class="text-surface-500 hover:text-surface-300 transition-colors">{{ $community->creator->name }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 card p-12 text-center">
                        <svg class="w-16 h-16 text-surface-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-surface-400 text-lg font-medium">{{ __('Сообществ пока нет.') }}</p>
                        <p class="text-surface-500 text-sm mt-1">{{ __('Создайте первое сообщество!') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $communities->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
