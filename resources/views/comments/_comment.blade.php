<div class="py-4 {{ $comment->parent_id ? 'ml-8 pl-4 border-l-2 border-surface-700/30' : '' }}">
    <div class="flex items-start gap-3">
        <div class="shrink-0">
            @if($comment->user->profile && $comment->user->profile->avatar)
                <img src="{{ Storage::url($comment->user->profile->avatar) }}" class="w-8 h-8 rounded-lg object-cover" alt="">
            @else
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400/80 to-brand-600/80 flex items-center justify-center text-xs font-bold text-white">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('profile.show', $comment->user) }}" class="text-sm font-medium text-surface-200 hover:text-white transition-colors">{{ $comment->user->name }}</a>
                <span class="text-xs text-surface-500">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-surface-300 leading-relaxed">{{ $comment->body }}</p>

            <div class="flex items-center gap-3 mt-2">
                @auth
                    <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs text-surface-500 hover:text-brand-400 transition-colors">
                        {{ __('Ответить') }}
                    </button>
                @endauth

                @can('update', $comment)
                    <button onclick="document.getElementById('edit-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs text-surface-500 hover:text-brand-400 transition-colors">
                        {{ __('Редактировать') }}
                    </button>
                @endcan

                @can('delete', $comment)
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Вы уверены?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400/70 hover:text-red-400 transition-colors">{{ __('Удалить') }}</button>
                    </form>
                @endcan
            </div>

            {{-- Reply Form --}}
            @auth
                <form id="reply-form-{{ $comment->id }}" action="{{ route('comments.store') }}" method="POST" class="hidden mt-3">
                    @csrf
                    <input type="hidden" name="article_id" value="{{ $comment->article_id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="body" rows="2" required placeholder="{{ __('Написать ответ...') }}" class="input-field text-sm resize-none"></textarea>
                    <x-primary-button class="mt-2 text-xs">{{ __('Ответить') }}</x-primary-button>
                </form>
            @endauth

            {{-- Edit Form --}}
            @can('update', $comment)
                <form id="edit-form-{{ $comment->id }}" action="{{ route('comments.update', $comment) }}" method="POST" class="hidden mt-3">
                    @csrf
                    @method('PUT')
                    <textarea name="body" rows="2" required class="input-field text-sm resize-none">{{ $comment->body }}</textarea>
                    <x-primary-button class="mt-2 text-xs">{{ __('Сохранить') }}</x-primary-button>
                </form>
            @endcan
        </div>
    </div>

    {{-- Nested replies --}}
    @if($comment->replies && $comment->replies->count())
        @foreach($comment->replies as $reply)
            @include('comments._comment', ['comment' => $reply])
        @endforeach
    @endif
</div>
