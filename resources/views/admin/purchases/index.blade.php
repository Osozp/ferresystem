<x-layouts::app title="Compras" :breadcrumbs="['admin.purchases.index' => 'Compras']">
    <flux:card>
        <div class="flex items-center justify-between gap-4">
            <div>
                <flux:heading>Compra de productos</flux:heading>
                <flux:text class="mt-1">Tus ultimas compras</flux:text>
            </div>

            <flux:button href="{{ route('admin.purchases.create') }}" variant="primary" size="sm" icon="plus">
                Comprar Producto
            </flux:button>
        </div>

        <flux:table bleed container:class="mt-6" id="compras">
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Fecha de compra</flux:table.column>
                <flux:table.column>Total</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column>Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($purchases as $purchase)
                    <flux:table.row :key="$purchase->id">
                        <flux:table.cell variant="strong">
                            {{ $purchase->id }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}
                        </flux:table.cell>

                        <flux:table.cell class="!font-bold !text-emerald-600">
                            Bs. {{ number_format($purchase->total, 2) }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($purchase->status === 'canceled')
                                <span
                                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-red-300">
                                    Anulada
                                </span>
                            @else
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-green-300">
                                    Completada
                                </span>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:button.group>
                                <flux:button icon="cursor-arrow-rays" variant="primary" color="blue"
                                    class="btn-show-purchase" data-id="{{ $purchase->id }}">
                                    Ver
                                </flux:button>
                                @if ($purchase->status !== 'canceled')
                                    <flux:button icon="trash" variant="primary" color="red"
                                        class="btn-cancel-purchase" data-id="{{ $purchase->id }}">
                                        Ver
                                    </flux:button>
                                @endif
                            </flux:button.group>
                        </flux:table.cell>



                        {{-- <flux:table.cell>
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
                        </flux:table.cell> --}}
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <!-- MODAL DE DETALLE DE COMPRA -->
    <div id="purchase-detail-modal"
        class="fixed inset-0 z-50 hidden bg-zinc-900/60 backdrop-blur-sm flex items-start justify-center p-4">
        <div
            class="bg-white dark:bg-white/10 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh] border border-transparent dark:border-zinc-800 transition-colors">

            <!-- Encabezado del Modal -->
            <div class="px-6 py-4 bg-emerald-600 dark:bg-emerald-700 text-white flex items-center justify-between">
                <h3 class="text-lg font-bold tracking-wide">
                    Detalle de Compra #<span id="modal-purchase-id"></span>
                </h3>
                <button id="close-modal-btn" type="button"
                    class="text-white/80 hover:text-white hover:bg-emerald-700 dark:hover:bg-emerald-800 p-1.5 rounded-lg transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="p-6 overflow-y-auto flex-1 space-y-5">
                <!-- Datos Generales -->
                <div
                    class="flex justify-between items-center text-sm text-zinc-600 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-800 pb-4">
                    <p>
                        <strong class="font-semibold text-zinc-800 dark:text-zinc-200">Fecha de compra:</strong>
                        <span id="modal-purchase-date"></span>
                    </p>
                    <p>
                        <strong class="font-semibold text-zinc-800 dark:text-zinc-200">Total general:</strong>
                        <span id="modal-purchase-total"
                            class="font-bold text-emerald-600 dark:text-emerald-400 text-base"></span> Bs.
                    </p>
                </div>

                <!-- Tabla de Productos -->
                <div class="overflow-x-auto rounded-xl border border-zinc-100 dark:border-zinc-800">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-xs uppercase bg-zinc-100 dark:bg-zinc-800/60 text-zinc-600 dark:text-zinc-400 font-semibold tracking-wider">
                            <tr>

                                <th class="px-4 py-3">Descripción</th>
                                <th class="px-4 py-3 text-center">Cantidad</th>
                                <th class="px-4 py-3 text-right">P. Unitario</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body"
                            class="divide-y divide-zinc-100 dark:divide-zinc-800 text-zinc-700 dark:text-zinc-300">
                            <!-- Filas cargadas dinámicamente con JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL DE DETALLE DE COMPRA -->
    {{-- <div id="purchase-detail-modal"
        class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-start justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Encabezado del Modal -->
            <div class="px-6 py-4 bg-emerald-600 text-white flex items-center justify-between">
                <h3 class="text-lg font-bold">Detalle de Compra #<span id="modal-purchase-id"></span></h3>
                <button id="close-modal-btn" type="button"
                    class="text-white hover:bg-emerald-700 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="p-6 overflow-y-auto flex-1 space-y-4">
                <div class="flex justify-between items-center text-sm text-gray-600 border-b pb-3">
                    <p><strong>Fecha de compra:</strong> <span id="modal-purchase-date"></span></p>
                    <p><strong>Total general:</strong> <span id="modal-purchase-total"
                            class="font-bold text-emerald-600 text-base"></span> Bs.</p>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-2">Código</th>
                            <th class="px-4 py-2">Descripción</th>
                            <th class="px-4 py-2 text-center">Cantidad</th>
                            <th class="px-4 py-2 text-right">P. Unitario</th>
                            <th class="px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modal-items-body" class="divide-y divide-gray-200">
                        <!-- Filas cargadas dinámicamente con JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

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

    @push('scripts')
        @vite(['resources/js/modules/compras.js'])
    @endpush

</x-layouts::app>
