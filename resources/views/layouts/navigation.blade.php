<nav x-data="{ open: false }" class="sticky top-0 z-50 glass border-b border-surface-700/50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand-400 to-brand-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-lg font-bold gradient-text hidden sm:inline">InfoSphere</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('articles.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('articles.index') || request()->routeIs('home') ? 'bg-brand-500/15 text-brand-300' : 'text-surface-400 hover:text-surface-200 hover:bg-surface-700/50' }}">
                        {{ __('Статьи') }}
                    </a>
                    <a href="{{ route('communities.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('communities.*') ? 'bg-brand-500/15 text-brand-300' : 'text-surface-400 hover:text-surface-200 hover:bg-surface-700/50' }}">
                        {{ __('Сообщества') }}
                    </a>
                    <a href="{{ route('search') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('search') ? 'bg-brand-500/15 text-brand-300' : 'text-surface-400 hover:text-surface-200 hover:bg-surface-700/50' }}">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('Поиск') }}
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @auth
                    @php($unreadCount = Auth::user()->unreadNotifications->count())
                    <!-- Write Article Button -->
                    <a href="{{ route('articles.create') }}" class="btn-primary text-xs gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Написать') }}
                    </a>

                    <!-- Notifications Bell -->
                    <a href="{{ route('notifications.index') }}" class="relative p-2.5 rounded-xl text-surface-400 hover:text-surface-200 hover:bg-surface-700/50 transition-all duration-200 {{ $unreadCount > 0 ? 'bg-surface-700/60 text-surface-200' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 w-4.5 h-4.5 flex items-center justify-center text-[10px] font-bold text-white rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-surface-300 hover:text-surface-100 hover:bg-surface-700/50 transition-all duration-200 focus:outline-none">
                                @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                    <img src="{{ Storage::url(Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-lg object-cover ring-1 ring-surface-600/50">
                                @else
                                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-xs font-bold text-white">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.show', Auth::user())">
                                {{ __('Мой профиль') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Настройки') }}
                            </x-dropdown-link>

                            @if(Auth::user()->isAdmin())
                                <x-dropdown-link :href="url('/admin')">
                                    {{ __('Панель админа') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-surface-700/50 my-1"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Выйти') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost text-sm">{{ __('Войти') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary text-sm">{{ __('Регистрация') }}</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-surface-400 hover:text-surface-200 hover:bg-surface-700/50 transition-all duration-200 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-surface-700/50">
        <div class="px-3 py-3 space-y-1">
            <a href="{{ route('articles.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('articles.index') ? 'bg-brand-500/15 text-brand-300' : 'text-surface-400 hover:bg-surface-700/50 hover:text-surface-200' }} transition-all duration-200">
                {{ __('Статьи') }}
            </a>
            <a href="{{ route('communities.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('communities.*') ? 'bg-brand-500/15 text-brand-300' : 'text-surface-400 hover:bg-surface-700/50 hover:text-surface-200' }} transition-all duration-200">
                {{ __('Сообщества') }}
            </a>
            <a href="{{ route('search') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('search') ? 'bg-brand-500/15 text-brand-300' : 'text-surface-400 hover:bg-surface-700/50 hover:text-surface-200' }} transition-all duration-200">
                {{ __('Поиск') }}
            </a>
        </div>

        @auth
            <div class="px-3 py-3 border-t border-surface-700/50">
                <div class="flex items-center gap-3 px-4 py-2 mb-2">
                    @if(Auth::user()->profile && Auth::user()->profile->avatar)
                        <img src="{{ Storage::url(Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-lg object-cover ring-1 ring-surface-600/50">
                    @else
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-sm text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-surface-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.show', Auth::user()) }}" class="block px-4 py-2.5 rounded-xl text-sm text-surface-400 hover:bg-surface-700/50 hover:text-surface-200 transition-all duration-200">
                        {{ __('Мой профиль') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 rounded-xl text-sm text-surface-400 hover:bg-surface-700/50 hover:text-surface-200 transition-all duration-200">
                        {{ __('Настройки') }}
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm text-surface-400 hover:bg-surface-700/50 hover:text-surface-200 transition-all duration-200">
                        {{ __('Уведомления') }}
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white rounded-full border border-white/30">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('articles.create') }}" class="block px-4 py-2.5 rounded-xl text-sm text-brand-400 hover:bg-brand-500/10 transition-all duration-200">
                        {{ __('Написать статью') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2.5 rounded-xl text-sm text-red-400 hover:bg-red-500/10 transition-all duration-200">
                            {{ __('Выйти') }}
                        </a>
                    </form>
                </div>
            </div>
        @else
            <div class="px-3 py-3 border-t border-surface-700/50 space-y-2">
                <a href="{{ route('login') }}" class="block text-center px-4 py-2.5 rounded-xl text-sm font-medium text-surface-300 hover:bg-surface-700/50 transition-all duration-200">
                    {{ __('Войти') }}
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block text-center btn-primary text-sm">
                        {{ __('Регистрация') }}
                    </a>
                @endif
            </div>
        @endauth
    </div>
</nav>
