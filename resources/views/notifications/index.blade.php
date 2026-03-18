<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ __('Уведомления') }}</h1>
                    <p class="text-surface-400 text-sm mt-1">{{ __('Ваши последние уведомления') }}</p>
                </div>
                <button onclick="markAllRead()" class="btn-ghost text-xs gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Прочитать все') }}
                </button>
            </div>

            <div class="card divide-y divide-surface-700/30 overflow-hidden">
                @forelse($notifications as $notification)
                    <div id="notification-{{ $notification->id }}" class="p-5 flex items-start gap-4 transition-opacity duration-300 {{ $notification->read_at ? 'opacity-50' : '' }}">
                        <div class="w-2 h-2 rounded-full mt-2 shrink-0 {{ $notification->read_at ? 'bg-surface-600' : 'bg-brand-400' }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-surface-200 leading-relaxed">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <p class="text-xs text-surface-500 mt-1.5">{{ $notification->created_at->locale('ru')->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            @if(isset($notification->data['article_slug']))
                                <a href="{{ route('articles.show', $notification->data['article_slug']) }}" onclick="markRead('{{ $notification->id }}')" class="link text-xs">{{ __('Перейти') }}</a>
                            @endif
                            @if(!$notification->read_at)
                                <button onclick="markRead('{{ $notification->id }}')" class="text-xs text-surface-500 hover:text-surface-300 transition-colors">{{ __('Прочитано') }}</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-surface-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <p class="text-surface-400 text-lg font-medium">{{ __('Уведомлений нет.') }}</p>
                        <p class="text-surface-500 text-sm mt-1">{{ __('Здесь будут появляться ваши уведомления') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@push('scripts')
<script>
    function markRead(id) {
        axios.post(`/notifications/${id}/read`)
            .then(() => {
                const el = document.getElementById('notification-' + id);
                if (el) el.classList.add('opacity-50');
            });
    }

    function markAllRead() {
        axios.post('/notifications/read-all')
            .then(() => {
                document.querySelectorAll('[id^="notification-"]').forEach(el => {
                    el.classList.add('opacity-50');
                });
            });
    }
</script>
@endpush
</x-app-layout>
