<x-layouts::app>
 <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Montos</flux:table.column>
                <flux:table.column>Fecha Cierre</flux:table.column>
                {{-- <flux:table.column>Stock</flux:table.column>
                <flux:table.column>Precio Venta</flux:table.column>
                <flux:table.column>Acciones</flux:table.column> --}}
            </flux:table.columns>


            <flux:table.rows>
                @foreach ($cashRegisters as $cashRegister)
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
        <div class="mt-4 text-white">
            {{-- {{ $products->links() }}
        </div> --}}
</x-layouts::app>
