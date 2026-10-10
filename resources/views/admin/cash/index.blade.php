<x-layouts::app>
    <section class="grid grid-cols-1 gap-6">
        <div class="rounded-lg">
            <flux:card class="p-6">

                <!-- TÍTULO DE LA VISTA -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-zinc-700">
                    <div>
                        <flux:heading level="1">Control de Caja (Arqueo)</flux:heading>
                        <flux:text class="mt-1">Gestiona la apertura, supervisa las operaciones activas y realiza el cierre de caja de tu comercio.</flux:text>
                    </div>
                </div>

                <!-- MENSAJES DE ALERTA O ÉXITO -->
                @if (session('success'))
                    <div class="mt-4 p-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- CASO 1: NO HAY CAJA ABIIERTA (MOSTRAR FORMULARIO DE APERTURA) -->
                @if (!$activeRegister)
                    <div class="mt-6 max-w-xl mx-auto bg-gray-50 dark:bg-white/5 p-6 rounded-lg border border-gray-200 dark:border-zinc-700">
                        <flux:heading level="2" class="mb-2 text-center">Abrir Nueva Caja</flux:heading>
                        <p class="text-sm text-gray-500 text-center mb-6">Ingresa el monto inicial (cambio o base en efectivo) con el que iniciarás el turno.</p>

                        <form action="{{ route('admin.cash.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="opening_balance" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                    Monto Inicial (Bs.)
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-sm font-medium text-gray-500">Bs.</span>
                                    </div>
                                    <input type="number"
                                           step="0.01"
                                           id="opening_balance"
                                           name="opening_balance"
                                           class="block w-full rounded-md border border-gray-300 bg-white dark:bg-white/10 py-2 pl-9 pr-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                                           value="0.00"
                                           min="0"
                                           required />
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <flux:button variant="primary" color="green" icon="lock-open" type="submit">
                                    Abrir Caja
                                </flux:button>
                            </div>
                        </form>
                    </div>

                <!-- CASO 2: HAY UNA CAJA ABIIERTA (MOSTRAR RESUMEN Y BOTÓN DE CIERRE) -->
                @else
                    @php
                        $totalSales = $activeRegister->sales()->sum('total');
                        $totalPurchases = $activeRegister->purchases()->sum('total');
                        $expectedBalance = $activeRegister->opening_balance + $totalSales - $totalPurchases;
                    @endphp

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Tarjeta Monto Inicial -->
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-lg border border-gray-200 dark:border-zinc-700">
                            <span class="text-xs text-gray-500 block">Monto Inicial (Base)</span>
                            <span class="text-xl font-bold text-zinc-700 dark:text-zinc-300">{{ number_format($activeRegister->opening_balance, 2) }} Bs.</span>
                        </div>

                        <!-- Tarjeta Total Ventas -->
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-lg border border-gray-200 dark:border-zinc-700">
                            <span class="text-xs text-gray-500 block">Ventas del Turno (+)</span>
                            <span class="text-xl font-bold text-emerald-600">{{ number_format($totalSales, 2) }} Bs.</span>
                        </div>

                        <!-- Tarjeta Total Compras -->
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-lg border border-gray-200 dark:border-zinc-700">
                            <span class="text-xs text-gray-500 block">Compras del Turno (-)</span>
                            <span class="text-xl font-bold text-red-500">{{ number_format($totalPurchases, 2) }} Bs.</span>
                        </div>

                        <!-- Tarjeta Monto Esperado -->
                        <div class="bg-emerald-50 dark:bg-emerald-950/30 p-4 rounded-lg border border-emerald-200 dark:border-emerald-800">
                            <span class="text-xs text-emerald-700 dark:text-emerald-400 block font-semibold">Efectivo Esperado en Caja</span>
                            <span class="text-xl font-extrabold text-emerald-800 dark:text-emerald-300">{{ number_format($expectedBalance, 2) }} Bs.</span>
                        </div>
                    </div>

                    <!-- FORMULARIO DE CIERRE DE CAJA -->
                    <div class="mt-8 max-w-xl mx-auto bg-gray-50 dark:bg-white/5 p-6 rounded-lg border border-gray-200 dark:border-zinc-700">
                        <flux:heading level="2" class="mb-2 text-center">Arqueo y Cierre de Caja</flux:heading>
                        <p class="text-sm text-gray-500 text-center mb-6">Cuenta el dinero físico que tienes en caja e introdúcelo abajo para calcular diferencias.</p>

                        <form action="{{ route('admin.cash.close', $activeRegister->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="closing_balance" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                    Efectivo Contado Físicamente (Bs.)
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-sm font-medium text-gray-500">Bs.</span>
                                    </div>
                                    <input type="number"
                                           step="0.01"
                                           id="closing_balance"
                                           name="closing_balance"
                                           class="block w-full rounded-md border border-gray-300 bg-white dark:bg-white/10 py-2 pl-9 pr-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                                           placeholder="0.00"
                                           min="0"
                                           required />
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                    Observaciones / Notas del Cierre (Opcional)
                                </label>
                                <textarea id="notes"
                                          name="notes"
                                          rows="2"
                                          class="block w-full rounded-md border border-gray-300 bg-white dark:bg-white/10 p-2 text-sm text-zinc-700 dark:text-zinc-300 focus:border-emerald-500"
                                          placeholder="Ej. Cambio entregado al siguiente turno, justificativos..."></textarea>
                            </div>

                            <div class="flex justify-end pt-2">
                                <flux:button variant="primary" color="red" icon="lock-closed" type="submit">
                                    Cerrar Caja
                                </flux:button>
                            </div>
                        </form>
                    </div>
                @endif

            </flux:card>
        </div>
    </section>
</x-layouts::app>
