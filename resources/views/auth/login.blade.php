<x-guest-layout>
    <h2 class="text-xl font-bold text-white text-center mb-1">{{ __('Вход в аккаунт') }}</h2>
    <p class="text-sm text-surface-400 text-center mb-6">{{ __('Рады видеть вас снова') }}</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Электронная почта')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Пароль')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-surface-600 bg-surface-800 text-brand-500 shadow-sm focus:ring-brand-500/50 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-surface-400">{{ __('Запомнить меня') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-400 hover:text-brand-300 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Забыли пароль?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center">
            {{ __('Войти') }}
        </x-primary-button>

        <p class="text-center text-sm text-surface-500">
            {{ __('Нет аккаунта?') }}
            <a href="{{ route('register') }}" class="text-brand-400 hover:text-brand-300 font-medium transition-colors">{{ __('Зарегистрироваться') }}</a>
        </p>
    </form>
</x-guest-layout>
