<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>
@php
    $navItems = [
        [
            'icon' => 'home',
            'label' => __('Dashboard'),
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'icon' => 'book-open',
            'label' => __('Usuarios'),
            'route' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
        [
            'icon' => 'clipboard',
            'label' => __('Categorias'),
            'route' => route('admin.categories.index'),
            'active' => request()->routeIs('admin.categories.index'),
        ],
        [
            'icon' => 'user-group',
            'label' => __('Productos'),
            'route' => route('admin.products.index'),
            'active' => request()->routeIs('admin.products.*'),
        ],
        [
            'icon' => 'envelope-open',
            'label' => __('Compras'),
            'route' => route('admin.purchases.index'),
            'active' => request()->routeIs('admin.purchases.*'),
        ],
        [
            'icon' => 'paper-airplane',
            'label' => __('Ventas'),
            'route' => route('admin.sales.index'),
            'active' => request()->routeIs('admin.sales.*'),
        ],
        [
            'icon' => 'paper-airplane',
            'label' => __('Registro de caja'),
            'route' => route('admin.cash.index'),
            'active' => request()->routeIs('admin.cash.*'),
        ],
    ];
@endphp

<body class="min-h-screen bg-white dark:bg-zinc-800">
    {{-- <flux:sidebar sticky collapsible="mobile" --}}
    <flux:sidebar sticky collapsible
        class="border-e border-zinc-200 bg-[var(--tw-menu-bg)] dark:border-zinc-700">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                @foreach ($navItems as $item)
                    <flux:sidebar.item icon="{{ $item['icon'] }}" :href="$item['route']" :current="$item['active']"
                        {{-- wire:navigate --}}
                        >
                        {{ $item['label'] }}
                    </flux:sidebar.item>
                @endforeach
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

   <flux:main class="bg-gray-900">
        {{ $slot }}
    </flux:main>
    @if (session()->has('impersonated_by'))
        <div class="bg-yellow-500 text-black px-4 py-2 text-center text-sm font-bold flex justify-between items-center">
            <span>⚠️ Estás viendo el sistema en modo suplantación (Comercio actual).</span>
            <a href="{{ route('admin.superadmin.commerces.leave') }}"
                class="bg-black text-white px-3 py-1 rounded text-xs">Volver a Super Admin</a>
        </div>
    @endif

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts

    @stack('scripts')
</body>

</html>
