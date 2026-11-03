<x-layouts::app title="Productos" :breadcrumbs="['admin.products.index' => 'Productos']">
    <flux:card>
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:text class="mb-1 text-sm">Categoria</flux:text>
                <flux:heading class="font-bold !text-2xl">{{ $category->name }}</flux:heading>
            </div>

            <flux:button href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" variant="primary"
                size="sm" icon="plus">Crear
                Producto
            </flux:button>
        </div>

        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Descripcion</flux:table.column>
                <flux:table.column>Stock</flux:table.column>
                <flux:table.column>Precio Venta</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($products as $product)
                    <flux:table.row :key="$product->id">
                        <flux:table.cell variant="strong">
                            {{ $products->firstItem() + $loop->index }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->description }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->stock }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->price }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button href="{{ route('admin.products.edit', $product) }}" icon="pencil-square"
                                    class="!bg-amber-500 !text-white"></flux:button>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
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
            {{ $products->links() }}
        </div>
    </flux:card>

    @if (session('success'))
        <div class="my-4 flex items-center rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
            role="alert">
            <svg class="me-3 inline h-4 w-4 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            <div>
                <span class="font-medium">¡Éxito!</span> {{ session('success') }}
            </div>
        </div>
    @endif
</x-layouts::app>
