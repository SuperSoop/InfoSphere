<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Community Header --}}
            <div class="card overflow-hidden animate-fade-in">
                @if($community->cover_image)
                    <div class="relative">
                        <img src="{{ Storage::url($community->cover_image) }}" alt="{{ $community->name }}" class="w-full h-56 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-900/80 via-surface-900/30 to-transparent"></div>
                    </div>
                @else
                    <div class="w-full h-40 bg-gradient-to-r from-brand-500/30 via-violet-500/20 to-brand-600/30"></div>
                @endif
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-2xl font-bold text-white">{{ $community->name }}</h1>
                                @if($community->is_private)
                                    <span class="badge-warning text-[10px]">
                                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        {{ __('Приват') }}
                                    </span>
                                @endif
                            </div>
                            @if($community->description)
                                <p class="text-surface-400 mt-2 leading-relaxed">{{ $community->description }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-surface-400">
                                <span>{{ __('Создано') }} <a href="{{ route('profile.show', $community->creator) }}" class="link">{{ $community->creator->name }}</a></span>
                                <span class="text-surface-600">&middot;</span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span id="subscriber-count">{{ $community->subscribers_count }}</span> {{ __('подписчиков') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 shrink-0">
                            @auth
                                <button
                                    id="subscribe-btn"
                                    onclick="toggleSubscribe({{ $community->id }})"
                                    class="{{ $isSubscribed ? 'btn-secondary' : 'btn-primary' }}"
                                >
                                    {{ $isSubscribed ? __('Отписаться') : __('Подписаться') }}
                                </button>
                            @endauth
                            @can('update', $community)
                                <a href="{{ route('communities.edit', $community->slug) }}" class="btn-ghost gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ __('Редактировать') }}
                                </a>
                            @endcan
                            @can('delete', $community)
                                <form action="{{ route('communities.destroy', $community->slug) }}" method="POST" onsubmit="return confirm('{{ __('Вы уверены?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">{{ __('Удалить') }}</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Community Articles --}}
            <div>
                <h2 class="section-title mb-6">{{ __('Статьи сообщества') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($articles as $article)
                        <x-article-card :article="$article" />
                    @empty
                        <div class="col-span-2 card p-12 text-center">
                            <svg class="w-12 h-12 text-surface-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <p class="text-surface-400">{{ __('В этом сообществе пока нет статей.') }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    function toggleSubscribe(communityId) {
        axios.post(`/communities/${communityId}/subscribe`)
            .then(response => {
                const btn = document.getElementById('subscribe-btn');
                const count = document.getElementById('subscriber-count');
                count.textContent = response.data.count;
                if (response.data.subscribed) {
                    btn.textContent = '{{ __("Отписаться") }}';
                    btn.className = 'btn-secondary';
                } else {
                    btn.textContent = '{{ __("Подписаться") }}';
                    btn.className = 'btn-primary';
                }
            });
    }
</script>
@endpush
</x-app-layout>
