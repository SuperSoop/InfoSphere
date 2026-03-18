<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'InfoSphere') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-surface-950 text-surface-200 min-h-screen flex flex-col">
        {{-- Navigation --}}
        <header class="glass sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <a href="/" class="text-xl font-bold gradient-text">InfoSphere</a>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary text-sm">{{ __('Главная') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost text-sm">{{ __('Войти') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary text-sm">{{ __('Регистрация') }}</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Hero Section --}}
        <main class="flex-1 flex items-center justify-center px-4">
            <div class="max-w-3xl mx-auto text-center py-20">
                <div class="inline-flex items-center gap-2 badge-brand mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ __('Платформа для обмена знаниями') }}
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    {{ __('Делитесь идеями.') }}<br>
                    <span class="gradient-text">{{ __('Вдохновляйте мир.') }}</span>
                </h1>

                <p class="text-lg text-surface-400 max-w-xl mx-auto mb-10 leading-relaxed">
                    {{ __('InfoSphere — современная платформа для публикации статей, создания сообществ и обмена знаниями с единомышленниками.') }}
                </p>

                <div class="flex items-center justify-center gap-4 flex-wrap">
                    @auth
                        <a href="{{ route('articles.index') }}" class="btn-primary text-base px-8 py-3 gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            {{ __('Читать статьи') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-3 gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            {{ __('Начать бесплатно') }}
                        </a>
                    @endauth
                    <a href="{{ route('communities.index') }}" class="btn-secondary text-base px-8 py-3 gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ __('Сообщества') }}
                    </a>
                </div>
            </div>
        </main>

        {{-- Features Section --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card p-6 text-center">
                    <div class="w-12 h-12 rounded-xl bg-brand-500/15 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ __('Публикуйте статьи') }}</h3>
                    <p class="text-surface-400 text-sm">{{ __('Создавайте и делитесь статьями с поддержкой категорий, тегов и изображений.') }}</p>
                </div>
                <div class="card p-6 text-center">
                    <div class="w-12 h-12 rounded-xl bg-violet-500/15 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ __('Создавайте сообщества') }}</h3>
                    <p class="text-surface-400 text-sm">{{ __('Объединяйтесь по интересам в публичных и приватных сообществах.') }}</p>
                </div>
                <div class="card p-6 text-center">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ __('Обсуждайте') }}</h3>
                    <p class="text-surface-400 text-sm">{{ __('Комментируйте, ставьте лайки и сохраняйте в избранное лучшие статьи.') }}</p>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="border-t border-surface-800 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-surface-500 text-sm">&copy; {{ date('Y') }} InfoSphere. {{ __('Все права защищены.') }}</p>
            </div>
        </footer>
    </body>
</html>
