<x-layouts::app title="Usuarios" :breadcrumbs="['admin.users.index' => 'Usuarios']">
    {{-- <flux:card>
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading>Usuarios</flux:heading>
                <flux:text class="mt-1">Lista de usuarios</flux:text>
            </div>

            <flux:button href="{{ route('admin.users.create') }}" variant="primary" size="sm" icon="plus">Crear
                Usuario
            </flux:button>
        </div>

        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                {{-- <flux:table.column>#</flux:table.column>
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Correo</flux:table.column>
                <flux:table.column>Rol</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($usuarios as $usuario)
                    {{-- <flux:table.row :key="$usuario->id">
                        <flux:table.cell>
                        {{ $usuarios->firstItem() + $loop->index }}
                    </flux:table.cell>

                    <flux:table.cell variant="strong">
                        <a href="{{ route('admin.users.show', $usuario) }}">
                            {{ $usuario->name }}
                        </a>
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $usuario->email }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $usuario->role }}
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($usuario->is_active)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Bloqueado</span>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="flex gap-2">
                            {{-- Botón de Bloquear / Activar
                            <form action="{{ route('admin.users.toggle', $usuario) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2 py-1 rounded {{ $usuario->is_active ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                    {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                </button>
                            </form>

                            {{-- Botón de Eliminar
                            <form action="{{ route('admin.users.destroy', $usuario) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este operario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card> --}}

    <flux:card>
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading>Categorías</flux:heading>
                <flux:text class="mt-1">Tu actividad más reciente en categorias</flux:text>
            </div>

            <flux:button href="{{ route('admin.users.create') }}" variant="primary" size="sm" icon="plus">Crear
                Usuario
            </flux:button>
        </div>

        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                {{-- <flux:table.column>#</flux:table.column> --}}
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Correo</flux:table.column>
                <flux:table.column>Rol</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($usuarios as $usuario)
                    {{-- <flux:table.row :key="$usuario->id">
                        <flux:table.cell>
                        {{ $usuarios->firstItem() + $loop->index }}
                    </flux:table.cell> --}}

                    <flux:table.cell variant="strong">
                        <a href="{{ route('admin.users.show', $usuario) }}">
                            {{ $usuario->name }}
                        </a>
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $usuario->email }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $usuario->role }}
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($usuario->is_active)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Bloqueado</span>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="flex gap-2">
                            {{-- Botón de Bloquear / Activar --}}
                            <form action="{{ route('admin.users.toggle', $usuario) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2 py-1 rounded {{ $usuario->is_active ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                    {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                </button>
                            </form>

                            {{-- Botón de Eliminar --}}
                            <form action="{{ route('admin.users.destroy', $usuario) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este operario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        {{-- <div class="mt-4 text-white">
            {{ $usuarios->links() }}
        </div> --}}
    </flux:card>
    @if (session('success'))
        <div class="my-4 flex items-center rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
            role="alert">
            <svg class="me-3 inline h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            <div>
                <span class="font-medium">¡Éxito!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {{ session('error') }}
        </div>
    @endif


    {{-- <section class="relative bg-white shadow-md rounded-lg mb-5">
                <div class="p-4">
                    <div class="border mt-4 rounded-lg">
                        <table class="w-full text-sm text-left rounded-lg bg-gray-200">
                            <thead class="text-sm text-body   border-b rounded-lg">
                                <tr class="">
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Correo
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Rol
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Fecha Nacimiento
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Estado
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Alta
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($usuarios as $usuario)
                                    <tr class="bg-neutral-primary border-b border-default">
                                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                            {{ $usuario->name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $usuario->email }}
                                        </td>
                                        <td class="px-4 py-2">
                                            @if ($usuario->is_active)
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Bloqueado</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 flex gap-2">

                                            <form action="{{ route('admin.users.toggle', $usuario) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-xs px-2 py-1 rounded {{ $usuario->is_active ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                                    {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                                </button>
                                            </form>


                                            <form action="{{ route('admin.users.destroy', $usuario) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este operario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $usuario->role }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-gray-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                                                </svg>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">22 May
                                                    2024</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">

                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="  inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                                <svg class="me-1 h-3 w-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M5 11.917 9.724 16.5 19 7.5" />
                                                </svg>
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">View
                                                details</button>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr class="bg-neutral-primary border-b border-default">
                                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                        Microsoft Surface Pro
                                    </th>
                                    <td class="px-6 py-4">
                                        White
                                    </td>
                                    <td class="px-6 py-4">
                                        Laptop PC
                                    </td>
                                    <td class="px-6 py-4">
                                        $1999
                                    </td>
                                    <td class="px-6 py-4">
                                        423
                                    </td>
                                </tr>
                                <tr class="bg-neutral-primary">
                                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                        Magic Mouse 2
                                    </th>
                                    <td class="px-6 py-4">
                                        Black
                                    </td>
                                    <td class="px-6 py-4">
                                        Accessories
                                    </td>
                                    <td class="px-6 py-4">
                                        $99
                                    </td>
                                    <td class="px-6 py-4">
                                        121
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>





                    <nav class="mt-2 flex items-center justify-center sm:mt-8" aria-label="Page navigation example">
                        <ul class="flex h-8 items-center -space-x-px text-sm">
                            <li>
                                <a href="#"
                                    class="ms-0 flex h-8 items-center justify-center rounded-s-lg border border-e-0 border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m15 19-7-7 7-7" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                            </li>
                            <li>
                                <a href="#" aria-current="page"
                                    class="z-10 flex h-8 items-center justify-center border border-primary-300 bg-primary-50 px-3 leading-tight text-primary-600 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex h-8 items-center justify-center rounded-e-lg border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m9 5 7 7-7 7" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </section> --}}
</x-layouts::app>
