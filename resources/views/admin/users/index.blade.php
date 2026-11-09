<x-layouts::app>
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
                    <flux:table.row :key="$usuario->id">
                        {{-- <flux:table.cell>
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
                            {{ $usuario->role_name }}
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
</x-layouts::app>
