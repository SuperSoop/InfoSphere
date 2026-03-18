<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center gap-2 text-surface-500 text-sm mb-1">
                    <a href="{{ route('articles.index') }}" class="hover:text-surface-300 transition-colors">{{ __('Статьи') }}</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-surface-400">{{ __('Тег') }}</span>
                </div>
                <h1 class="text-2xl font-bold text-white">#{{ $tag->name }}</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($articles as $article)
                    <x-article-card :article="$article" />
                @empty
                    <div class="col-span-3 card p-12 text-center">
                        <p class="text-surface-400 text-lg font-medium">{{ __('Статей с этим тегом нет.') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
