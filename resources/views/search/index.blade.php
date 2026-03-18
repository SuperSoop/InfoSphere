<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white">{{ __('Поиск') }}</h1>
                <p class="text-surface-400 text-sm mt-1">{{ __('Найдите интересующие вас статьи') }}</p>
            </div>

            {{-- Search Form --}}
            <form action="{{ route('search') }}" method="GET" class="card p-5 mb-8" x-data="{ filtersOpen: false }">
                <div class="flex flex-col gap-3">
                    {{-- Search row: input + Find button --}}
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="relative min-w-0 flex-1">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="q" value="{{ $query }}" placeholder="{{ __('Поиск статей...') }}" class="input-field pl-10 w-full">
                        </div>
                        <x-primary-button class="whitespace-nowrap md:px-5">{{ __('Найти') }}</x-primary-button>
                    </div>

                    {{-- Filters row --}}
                    <div class="relative inline-block" @keydown.escape.window="filtersOpen = false">
                        <button type="button" class="btn-secondary whitespace-nowrap px-4 py-3 gap-2" @click="filtersOpen = !filtersOpen" :aria-expanded="filtersOpen.toString()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.5V19a1 1 0 01-.553.894l-3 1.5A1 1 0 019 20.5v-7L3.2 4.6A1 1 0 013 4z"/>
                            </svg>
                            <span>{{ __('Фильтры') }}</span>
                        </button>
                    </div>
            
                    <div
                        x-cloak
                        x-show="filtersOpen"
                        x-transition.origin.top
                        @click.outside="filtersOpen = false"
                        class="w-full"
                    >
                        <div class="glass-strong rounded-2xl p-4 shadow-xl space-y-4">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="min-w-0">
                                    <label class="form-label mb-2">{{ __('Категория') }}</label>
                                    <select name="category" class="select-field">
                                        <option value="">{{ __('Все категории') }}</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="min-w-0">
                                    <label class="form-label mb-2">{{ __('Тег') }}</label>
                                    <select name="tag" class="select-field">
                                        <option value="">{{ __('Все теги') }}</option>
                                        @foreach($tags as $t)
                                            <option value="{{ $t->id }}" {{ $tagId == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="min-w-0">
                                    <label class="form-label mb-2">{{ __('Сортировка') }}</label>
                                    <select name="sort" class="select-field">
                                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>{{ __('Новые') }}</option>
                                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>{{ __('Популярные') }}</option>
                                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>{{ __('Старые') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="btn-ghost" @click="filtersOpen = false">{{ __('Готово') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if($query)
                <p class="text-sm text-surface-400 mb-6">{{ __('Результаты по запросу') }}: <span class="text-white font-medium">"{{ $query }}"</span></p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($articles as $article)
                    <x-article-card :article="$article" />
                @empty
                    <div class="col-span-3 card p-12 text-center">
                        <svg class="w-16 h-16 text-surface-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <p class="text-surface-400 text-lg font-medium">{{ __('Статьи не найдены.') }}</p>
                        <p class="text-surface-500 text-sm mt-1">{{ __('Попробуйте изменить параметры поиска') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

