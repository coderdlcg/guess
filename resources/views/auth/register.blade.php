<x-app-layout>
    <x-auth-card>
        <x-slot name="logo" class="block-auth">
            <div class="log_reg">
                <div class="reg active">
                    <a href="{{ route('register') }}">Регистрация</a>
                </div>
                <div class="delim">
                    /
                </div>
                <div class="log in_active">
                    <a href="{{ route('login') }}">Авторизация</a>
                </div>
            </div>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="block-auth">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" placeholder="Введите имя" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" value="E-mail" />

                <x-input id="email" class="block mt-1 w-full" type="email" placeholder="Введите email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                placeholder="*******"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                placeholder="*******"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="btn-sign-in ">
                    {{ __('Sign up') }}
                </x-button>
            </div>

            <div class="block block-footer mt-4">
                <span class="quest">Уже есть аккаунт?</span>
                <a class="underline text-sm" href="{{ route('login') }}">
                    Авторизация
                </a>
            </div>
        </form>
    </x-auth-card>
</x-app-layout>
