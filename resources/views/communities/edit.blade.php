<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white">{{ __('Редактировать сообщество') }}</h1>
                <p class="text-surface-400 text-sm mt-1">{{ $community->name }}</p>
            </div>

            <div class="card p-6 sm:p-8">
                <form action="{{ route('communities.update', $community->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Название')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $community->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Описание')" />
                        <textarea id="description" name="description" rows="4" class="input-field mt-1">{{ old('description', $community->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="cover_image" :value="__('Обложка')" />
                        @if($community->cover_image)
                            <img src="{{ Storage::url($community->cover_image) }}" alt="" class="w-40 h-24 object-cover rounded-xl mb-3 border border-surface-700/50">
                        @endif
                        <div class="flex items-center gap-4">
                            <label for="cover_image" class="btn-secondary cursor-pointer gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ __('Заменить файл') }}
                            </label>
                            <input id="cover_image" name="cover_image" type="file" accept="image/*" class="hidden" />
                            <span id="cover-name" class="text-sm text-surface-500">{{ __('Файл не выбран') }}</span>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2.5 px-4 py-3 rounded-xl bg-surface-700/30 border border-surface-600/30 cursor-pointer hover:border-brand-500/30 has-[:checked]:bg-brand-500/10 has-[:checked]:border-brand-500/30 transition-all duration-200">
                            <input type="checkbox" name="is_private" value="1" {{ old('is_private', $community->is_private) ? 'checked' : '' }} class="rounded border-surface-600 bg-surface-800 text-brand-500 shadow-sm focus:ring-brand-500/50 focus:ring-offset-0">
                            <div>
                                <span class="text-sm font-medium text-surface-300">{{ __('Приватное сообщество') }}</span>
                                <p class="text-xs text-surface-500 mt-0.5">{{ __('Только одобренные участники смогут видеть контент') }}</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-surface-700/30">
                        <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                        <a href="{{ route('communities.show', $community->slug) }}" class="text-sm text-surface-400 hover:text-surface-200 transition-colors">{{ __('Отмена') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.getElementById('cover_image').addEventListener('change', function() {
        document.getElementById('cover-name').textContent = this.files[0] ? this.files[0].name : '{{ __("Файл не выбран") }}';
    });
</script>
@endpush
</x-app-layout>
