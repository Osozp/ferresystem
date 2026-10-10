<x-layouts::app>
    <flux:card>

    </flux:card>
    <flux:card>
        <section class="flex">
            <flux:button variant="primary" href="{{ route('admin.sales.create') }}">Crear Venta</flux:button>
        </section>
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div class="mx-auto px-4 2xl:px-0">
                <div class="mx-auto max-w-3xl space-y-6 sm:space-y-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                        Ventas del dia
                    </h2>

                    <ol class="relative border-s border-gray-200 dark:border-gray-700">
                        @forelse ($sales as $sale)
                            <li class="mb-10 ms-6">
                                <span
                                    class="absolute -start-2.5 flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white dark:bg-gray-800 dark:ring-gray-900">
                                    <svg class="h-3 w-3 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                </span>
                                <span
                                    class="inline-flex items-center rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                    {{ $sale->total }} Bs. <span
                                        class="ml-2 text-green-600">{{ $sale->created_at }}</span>
                                </span>
                                @foreach ($sale->products as $product)
                                    <h3
                                        class="mb-0.5 mt-2 text-lg font-semibold text-primary-800 dark:text-primary-300">
                                        {{ $product->description }}
                                    </h3>
                                @endforeach
                                <p class="text-base font-normal text-primary-700 dark:text-primary-300">
                                    Please pack the product
                                    and accessories received in the original packaging. The courier will contact you to
                                    pick
                                    up
                                    the package from the specified address.
                                </p>
                            </li>
                        @empty
                            <p class="text-gray-500 text-center py-4">
                                No se encontraron productos disponibles.
                            </p>
                        @endforelse
                    </ol>

                    <div class="sm:flex items-center sm:space-x-4 space-y-4 sm:space-y-0">
                        <a href="#" title=""
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                            role="button">
                            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4" />
                            </svg>
                            Back to your account
                        </a>
                        <button type="button"
                            class="w-full sm:w-auto flex justify-center items-center rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                            <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                            </svg>
                            Cancel the refund
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </flux:card>


    @push('script')
        @vite('resources/js/modules/ventas.js')

        @if (session('success'))
            <script>
                setTimeout(() => {
                    if (typeof window.showToast === 'function') {
                        window.showToast('success', @json(session('success')));
                    }
                }, 50);
            </script>
        @endif
    @endpush

    <!-- MODAL DE DETALLE DE COMPRA -->
    <div id="purchase-detail-modal"
        class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
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
    </div>
</x-layouts::app>
