<x-layouts::app>
    <flux:card>
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading>Categorías</flux:heading>
                <flux:text class="mt-1">Tu actividad más reciente en categorias</flux:text>
            </div>

            <flux:button href="{{ route('admin.categories.create') }}" variant="primary" size="sm" icon="plus">Crear
                Categoría
            </flux:button>
        </div>

        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Cant Productos</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($categories as $category)
                    <flux:table.row :key="$category->id">
                        <flux:table.cell variant="strong">{{ $category->name }}</flux:table.cell>
                        <flux:table.cell>{{ $category->products_count }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button href="{{ route('admin.categories.edit', $category) }}" icon="pencil-square"
                                    class="!bg-amber-500 !text-white"></flux:button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" icon="archive-box-x-mark"
                                        class="!bg-red-500 !text-white"></flux:button>
                                </form>
                            </flux:button.group>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {{ session('error') }}
        </div>
    @endif

</x-layouts::app>
