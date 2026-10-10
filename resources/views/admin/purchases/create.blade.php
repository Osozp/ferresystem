<x-layouts::app>
    <section class="grid grid-cols-2 gap-4">
        <div class="rounded-lg">
            <flux:card class="!px-3">
                <form action="{{ route('admin.purchases.store') }}" method="POST" class="flex flex-col flex-1 min-h-0"
                    id="table-compras">
                    @csrf
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <flux:heading>Productos a la venta</flux:heading>
                            {{-- <flux:text class="mt-1">Your latest customer activity.</flux:text> --}}
                        </div>

                        {{-- <flux:button href="{{ route('admin.products.create') }}" variant="primary" size="sm"
                            icon="plus">Crear
                            Producto
                        </flux:button> --}}
                    </div>

                    <flux:table bleed container:class="mt-6">
                        <flux:table.columns>
                            <flux:table.column>Descripcion</flux:table.column>
                            <flux:table.column>Cantidad</flux:table.column>
                            <flux:table.column>Precio U.</flux:table.column>
                            <flux:table.column>Precio Venta</flux:table.column>
                            <flux:table.column>Subtotal</flux:table.column>

                        </flux:table.columns>
                    </flux:table>
                    <div id="purchase-details-container">

                    </div>

                    <!-- 3. PIE DE PÁGINA (SIEMPRE FIJO AL FINAL) -->
                    <div class="mt-auto pt-3 border-t border-gray-200 flex flex-col gap-3">

                        <!-- Fila del Total -->
                        <div class="flex justify-end items-center gap-2">
                            <span class="text-zinc-700 dark:text-zinc-300 font-semibold text-sm">Total General:</span>
                            <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-500">
                                <span id="total-display">0.00</span> Bs.
                            </span>
                            <input type="hidden" name="total" id="total-input" value="0">
                        </div>

                        <!-- Fila de Fecha y Guardar -->
                        <div class="flex items-end justify-between gap-3 pt-2">
                            <!-- Campo Fecha -->
                            <div class="w-full sm:w-auto">
                                <label for="purchase_date"
                                    class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                    Fecha de Compra
                                </label>
                                <input type="date" id="purchase_date" name="purchase_date"
                                    class="w-full sm:w-48 rounded-md border border-gray-300 bg-gray-50 dark:bg-white/10 p-2 text-sm text-zinc-700 dark:text-zinc-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <flux:button variant="primary" color="green" icon="check" type="submit">
                                Guardar Compra
                            </flux:button>

                        </div>

                    </div>
                </form>
            </flux:card>
        </div>
        <div class="rounded-lg">
            <flux:card>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <flux:heading>Lista de productos</flux:heading>
                        {{-- <flux:text class="mt-1">Your latest customer activity.</flux:text> --}}
                    </div>

                    {{-- <flux:button href="{{ route('admin.products.create') }}" variant="primary" size="sm"
                        icon="plus">Crear
                        Producto
                    </flux:button> --}}
                </div>

                <flux:table bleed container:class="mt-6">
                    <flux:table.columns>
                        <flux:table.column>#</flux:table.column>
                        <flux:table.column>Codigo</flux:table.column>
                        <flux:table.column>Descripcion</flux:table.column>
                        <flux:table.column>Precio</flux:table.column>
                        <flux:table.column>Stock</flux:table.column>
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
                                    {{ $product->price }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ $product->stock }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:button variant="primary" color="green" data-action="btn-add-product"
                                        data-id="{{ $product->id }}" data-code="{{ $product->codigo }}"
                                        data-description="{{ $product->description }}">Agregar</flux:button>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>
    </section>

   @push('scripts')
        @vite(['resources/js/modules/compras.js'])
    @endpush

</x-layouts::app>
