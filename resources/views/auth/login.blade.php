<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo" class="block-auth">
            <div class="log_reg">
                <div class="reg in_active">
                    <a href="{{ route('register') }}">Регистрация</a>
                </div>
                <div class="delim">
                    /
                </div>
                <div class="log active">
                    <a href="{{ route('login') }}">Авторизация</a>
                </div>
            </div>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" class="block-auth">
        @csrf

        <!-- Email Address -->
            <div>
                <x-label class="label_email" for="email" value="E-mail" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Введите e-mail" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label class="label_email" for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         placeholder="*******"
                         required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="btn-sign-in ">
                    {{ __('Sign in') }}
                </x-button>
            </div>

            <div class="block block-footer mt-4">
                <span class="quest">Нет аккаунта?</span>
                <a class="underline text-sm" href="{{ route('register') }}">
                    Регистрация
                </a>
            </div>
        </form>

    </x-auth-card>
</x-guest-layout>
