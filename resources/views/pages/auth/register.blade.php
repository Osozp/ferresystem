<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingresa tus datos para crear tu cuenta')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />



        {{-- <a href="{{ route('auth.google') }}"
            class="w-full flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 font-medium py-2.5 px-4 rounded-lg hover:bg-gray-50 transition shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4"
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853"
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05"
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                <path fill="#EA4335"
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
            </svg>
            <span>Continuar con Google</span>
        </a> --}}

        <form id="register-form" method="POST" action="{{ route('register.store') }}" novalidate
            class="flex flex-col gap-6">
            @csrf

            <!-- PASO 1: Email -->
            <div id="step-1" class="flex flex-col gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('Email') }}
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        placeholder="email@gmail.com"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white" />
                    @error('email')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="button" id="btn-next"
                    class="w-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 py-2 px-4 rounded-md font-medium text-sm hover:opacity-90 transition">
                    {{ __('Siguiente') }}
                </button>
            </div>

            <!-- PASO 2: Nombre y Contraseñas (Oculto por defecto) -->
            <div id="step-2" class="flex flex-col gap-6 hidden">
                <div class="flex justify-between items-center text-sm text-zinc-500 dark:text-zinc-400">
                    <span id="display-email" class="font-medium text-zinc-800 dark:text-zinc-200"></span>
                    <button type="button" id="btn-back" class="text-xs underline hover:text-zinc-700">
                        {{ __('Change email') }}
                    </button>
                </div>

                <!-- Name -->
                <div>
                    <label for="commerce_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('Nombre del Comercio') }}
                    </label>
                    <input id="commerce_name" name="commerce_name" type="text" value="{{ old('name') }}" required
                        placeholder="{{ __('Nombre del comercio') }}"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white" />
                    @error('commerce_name')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('Password') }}
                    </label>
                    <input id="password" name="password" type="password" required placeholder="{{ __('Password') }}"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white" />
                    @error('password')
                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        {{ __('Confirm password') }}
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        placeholder="{{ __('Confirm password') }}"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white" />
                </div>

                <button type="submit"
                    class="w-full bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 py-2 px-4 rounded-md font-medium text-sm hover:opacity-90 transition">
                    {{ __('Create account') }}
                </button>
            </div>
        </form>

        {{-- <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete=""
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form> --}}

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Si ya tienes una cuenta') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Inicia sesión') }}</flux:link>
        </div>
    </div>


</x-layouts::auth>
