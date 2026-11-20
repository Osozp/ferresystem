<flux:card>
    <div class="flex items-center justify-between gap-4">
        <div>
            <flux:heading>Lista de productos</flux:heading>
        </div>
        <!-- Input de búsqueda integrado -->
        <div>
            <input type="text" id="product-search-input" value="{{ request('search') }}" placeholder="Buscar producto..."
                   class="rounded-md border border-gray-300 bg-gray-50 dark:bg-white/10 px-3 py-1.5 text-sm text-zinc-700 dark:text-zinc-300 focus:border-emerald-500 focus:outline-none">
        </div>
    </div>

    <flux:table bleed container:class="mt-6">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Descripcion</flux:table.column>
            <flux:table.column>Precio</flux:table.column>
            <flux:table.column>Stock</flux:table.column>
            <flux:table.column>Acciones</flux:table.column>
        </flux:table.columns>

        <flux:table.rows id="products-table-rows">
            @foreach ($products as $product)
                <flux:table.row :key="$product->id">
                    <flux:table.cell variant="strong">
                        {{ $product->id }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $product->description }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $product->price }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $product->stock }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{-- Verificamos si ya está agregado en la izquierda para deshabilitarlo visualmente si es necesario --}}
                        <flux:button variant="primary" color="green" data-action="btn-add-product"
                            data-id="{{ $product->id }}" data-code="{{ $product->codigo ?? '' }}"
                            data-description="{{ $product->description }}">Agregar</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <div class="mt-4 text-white" id="products-pagination-container">
        {{ $products->links() }}
    </div>
</flux:card>
