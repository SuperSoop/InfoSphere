<section>
    <header>
        <h2 class="text-lg font-semibold text-white">
            {{ __('Информация профиля') }}
        </h2>

        <p class="mt-1 text-sm text-surface-400">
            {{ __('Обновите информацию профиля и адрес электронной почты.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="avatar" :value="__('Аватар')" />
            <div class="mt-1 flex items-center gap-4">
                @if($user->profile && $user->profile->avatar)
                    <img src="{{ Storage::url($user->profile->avatar) }}" alt="Avatar" class="w-16 h-16 rounded-xl object-cover ring-2 ring-brand-500/20">
                @else
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <label for="avatar" class="btn-secondary cursor-pointer gap-2 text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('Изменить') }}
                </label>
                <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Имя')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Электронная почта')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-surface-300">
                        {{ __('Ваш адрес электронной почты не подтверждён.') }}

                        <button form="send-verification" class="text-sm link">
                            {{ __('Нажмите здесь, чтобы отправить письмо подтверждения повторно.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('Новая ссылка для подтверждения была отправлена на ваш адрес.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="bio" :value="__('О себе')" />
            <textarea id="bio" name="bio" rows="3" class="input-field mt-1">{{ old('bio', $user->profile?->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400"
                >{{ __('Сохранено.') }}</p>
            @endif
        </div>
    </form>
</section>
