<x-layouts::app>
    <div class="container mx-auto px-4 py-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Registrar Nuevo Operario</h1>

        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Operario</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña Temporal</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar Operario</button>
            </div>
        </form>
    </div>
</x-layouts::app>
