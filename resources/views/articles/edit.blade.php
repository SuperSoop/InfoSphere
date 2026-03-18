<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white">{{ __('Редактировать статью') }}</h1>
                <p class="text-surface-400 text-sm mt-1">{{ $article->title }}</p>
            </div>

            <div class="card p-6 sm:p-8">
                <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" :value="__('Заголовок')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $article->title)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="category_id" :value="__('Категория')" />
                            <select id="category_id" name="category_id" class="select-field mt-1">
                                <option value="">{{ __('— Нет —') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="community_id" :value="__('Сообщество')" />
                            <select id="community_id" name="community_id" class="select-field mt-1">
                                <option value="">{{ __('— Нет —') }}</option>
                                @foreach($communities as $community)
                                    <option value="{{ $community->id }}" {{ old('community_id', $article->community_id) == $community->id ? 'selected' : '' }}>{{ $community->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('community_id')" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Статус')" />
                            <select id="status" name="status" class="select-field mt-1">
                                <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>{{ __('Черновик') }}</option>
                                <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>{{ __('Опубликовано') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="tags" :value="__('Теги')" />
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-700/30 border border-surface-600/30 cursor-pointer hover:border-brand-500/30 has-[:checked]:bg-brand-500/15 has-[:checked]:border-brand-500/30 has-[:checked]:text-brand-300 transition-all duration-200 text-sm text-surface-400">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }} class="rounded border-surface-600 bg-surface-800 text-brand-500 shadow-sm focus:ring-brand-500/50 focus:ring-offset-0">
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                    </div>

                    <div>
                        <x-input-label for="excerpt" :value="__('Краткое описание')" />
                        <textarea id="excerpt" name="excerpt" rows="2" class="input-field mt-1 resize-none" maxlength="500">{{ old('excerpt', $article->excerpt) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('excerpt')" />
                    </div>

                    <div>
                        <x-input-label for="content" :value="__('Содержание')" />
                        <textarea id="content" name="content" rows="12" required class="input-field mt-1">{{ old('content', $article->content) }}</textarea>
                        <p class="mt-2 text-xs text-surface-500">{{ __('Для блока кода используйте тройные обратные кавычки: ```') }}</p>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>

                    <div>
                        <x-input-label for="image" :value="__('Изображение')" />
                        @if($article->image)
                            <img src="{{ Storage::url($article->image) }}" class="w-40 h-24 object-cover rounded-xl mb-3 border border-surface-700/50" alt="">
                        @endif
                        <div class="flex items-center gap-4">
                            <label for="image" class="btn-secondary cursor-pointer gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ __('Заменить файл') }}
                            </label>
                            <input id="image" name="image" type="file" accept="image/*" class="hidden" />
                            <span id="image-name" class="text-sm text-surface-500">{{ __('Файл не выбран') }}</span>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-surface-700/30">
                        <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                        <a href="{{ route('articles.show', $article->slug) }}" class="text-sm text-surface-400 hover:text-surface-200 transition-colors">{{ __('Отмена') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function() {
        document.getElementById('image-name').textContent = this.files[0] ? this.files[0].name : '{{ __("Файл не выбран") }}';
    });
</script>
@endpush
</x-app-layout>
