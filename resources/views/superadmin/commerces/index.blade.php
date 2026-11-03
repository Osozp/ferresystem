<x-layouts::app>
    {{-- Tarjetas de Métricas Globales --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm font-semibold">Total Comercios</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalCommerces }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
            <p class="text-gray-500 text-sm font-semibold">Comercios Activos</p>
            <p class="text-2xl font-bold text-gray-800">{{ $activeCommerces }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm font-semibold">En Período de Prueba</p>
            <p class="text-2xl font-bold text-gray-800">{{ $trialCommerces }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-indigo-500">
            <p class="text-gray-500 text-sm font-semibold">Ingresos Totales</p>
            <p class="text-2xl font-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión Global de Comercios</h1>
            <!-- Botón para modal de nuevo comercio si lo deseas -->
        </div>

        {{-- Filtros y Búsqueda --}}
        <form method="GET" action="{{ route('admin.superadmin.commerces.index') }}" class="mb-6 flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar por nombre o slug..." class="border rounded px-4 py-2 w-1/3">

            <select name="status" class="border rounded px-4 py-2">
                <option value="">-- Todos los estados --</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                <option value="trialing" {{ request('status') == 'trialing' ? 'selected' : '' }}>En Prueba</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendidos</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrar</button>
        </form>

        {{-- Tabla de Comercios --}}
        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Comercio</th>
                        <th class="py-3 px-6 text-left">Slug</th>
                        <th class="py-3 px-6 text-center">Estado</th>
                        <th class="py-3 px-6 text-center">Vencimiento Prueba</th>
                        <th class="py-3 px-6 text-center">Vencimiento Suscripción</th>
                        <th class="py-3 px-6 text-center">Renovacion</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($commerces as $commerce)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left font-bold">{{ $commerce->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $commerce->slug }}</td>
                            <td class="py-3 px-6 text-center">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                            @if ($commerce->status === 'active') bg-green-200 text-green-800
                            @elseif($commerce->status === 'trialing') bg-yellow-200 text-yellow-800
                            @else bg-red-200 text-red-800 @endif">
                                    {{ strtoupper($commerce->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                {{ $commerce->trial_ends_at ? $commerce->trial_ends_at->format('Y-m-d') : '-' }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                {{ $commerce->subscription_ends_at ? $commerce->subscription_ends_at->format('Y-m-d') : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.superadmin.commerces.impersonate', $commerce) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-2 py-1 rounded">
                                    Entrar como...
                                </a>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <form action="{{ route('admin.superadmin.commerces.extend', $commerce) }}"
                                    method="POST" class="mt-2 flex flex-col gap-1 bg-gray-50 p-2 rounded border">
                                    @csrf
                                    <div class="flex gap-1">
                                        <input type="number" name="days" placeholder="Días (ej. 30)" value="30"
                                            class="w-16 text-xs border rounded p-1" required>
                                        <input type="number" step="0.01" name="amount" placeholder="Monto"
                                            class="w-20 text-xs border rounded p-1" required>
                                        <input type="text" name="reference" placeholder="Ref. pago"
                                            class="w-24 text-xs border rounded p-1">
                                    </div>
                                    <button type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white text-xs px-2 py-1 rounded">
                                        Renovar / Extender
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-6 text-center">
                                {{-- Formulario rápido para cambiar estado / suspender --}}
                                <form action="{{ route('admin.superadmin.commerces.updateStatus', $commerce) }}"
                                    method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="text-xs border rounded p-1">
                                        <option value="active" {{ $commerce->status === 'active' ? 'selected' : '' }}>
                                            Activo</option>
                                        <option value="trialing"
                                            {{ $commerce->status === 'trialing' ? 'selected' : '' }}>Prueba</option>
                                        <option value="suspended"
                                            {{ $commerce->status === 'suspended' ? 'selected' : '' }}>Suspender
                                        </option>
                                    </select>
                                    <button type="submit"
                                        class="bg-gray-800 text-white text-xs px-2 py-1 rounded">Guardar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $commerces->links() }}
    </div>
</x-layouts::app>
