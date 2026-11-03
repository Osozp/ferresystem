<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
    @if (session()->has('impersonated_by'))
        <div class="bg-yellow-500 text-black px-4 py-2 text-center text-sm font-bold flex justify-between items-center">
            <span>⚠️ Estás viendo el sistema en modo suplantación (Comercio actual).</span>
            <a href="{{ route('admin.superadmin.commerces.leave') }}"
                class="bg-black text-white px-3 py-1 rounded text-xs">Volver a Super Admin</a>
        </div>
    @endif
</x-layouts::app.sidebar>
