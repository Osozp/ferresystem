<x-layouts::app>
    {{-- <flux:table bleed container:class="mt-6">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Montos</flux:table.column>
            <flux:table.column>Fecha Cierre</flux:table.column>

        </flux:table.columns>


        <flux:table.rows>
            @forelse ($cashRegisters as $cashRegister)
                <flux:table.row :key="$cashRegister->id">
                    <flux:table.cell variant="strong">
                        {{ $cashRegisters->firstItem() + $loop->index }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $cashRegister->closing_balance }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $cashRegister->closed_at }}
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="3">
                        <div class="flex items-center justify-center py-4 text-sm text-gray-500">
                            No hay registros de cierres de caja.
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table> --}}
    <div class="mt-4 text-white">
        {{-- {{ $products->links() }}
        </div> --}}
</x-layouts::app>
