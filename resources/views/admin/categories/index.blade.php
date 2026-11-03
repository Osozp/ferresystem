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
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Nombre</flux:table.column>
                <flux:table.column>Cant Productos</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($categories as $category)
                    <flux:table.row :key="$category->id">
                        <flux:table.cell>
                            {{ $categories->firstItem() + $loop->index }}
                        </flux:table.cell>

                        <flux:table.cell variant="strong">
                            <a href="{{ route('admin.categories.show', $category) }}">
                                {{ $category->name }}
                            </a>
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $category->products_count }}
                        </flux:table.cell>

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
        <div class="mt-4 text-white">
            {{ $categories->links() }}
        </div>
    </flux:card>
    @if (session('success'))
        <div class="my-4 flex items-center rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
            role="alert">
            <svg class="me-3 inline h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
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
