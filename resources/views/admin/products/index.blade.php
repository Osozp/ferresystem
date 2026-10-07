<x-layouts::app title="Productos" :breadcrumbs="['admin.products.index' => 'Productos']">
    <flux:card>
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading>Recent customers</flux:heading>
                <flux:text class="mt-1">Your latest customer activity.</flux:text>
            </div>

            <flux:button href="{{ route('admin.products.create') }}" variant="primary" size="sm" icon="plus">Crear
                Producto
            </flux:button>
        </div>

        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Codigo</flux:table.column>
                <flux:table.column>Descripcion</flux:table.column>
                <flux:table.column>Categoria</flux:table.column>
                <flux:table.column>Stock</flux:table.column>
                <flux:table.column>Precio Venta</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($products as $product)
                    <flux:table.row :key="$product->id">
                        <flux:table.cell variant="strong">
                            {{ $product->id }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->codigo }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->description }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $product->category->name }}
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
    </flux:card>
</x-layouts::app>
