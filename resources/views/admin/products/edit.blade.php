<x-layouts::app title="Productos" :breadcrumbs="['admin.products.index' => 'Productos']">
    <flux:card>
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <flux:fieldset>
                <flux:legend>Editar producto</flux:legend>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <flux:select label="Categoria" name="category_id">
                                <flux:select.option value="" disabled>Seleccione una categoria</flux:select.option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>
                        {{-- <div class="sm:col-span-3">
                            <flux:input label="Codigo" name="codigo" placeholder="Codigo del producto"
                                value="{{ $product->codigo }}" />
                        </div> --}}
                    </div>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">


                        <div class="sm:col-span-3">
                            <flux:input label="Descripcion" name="description" placeholder="Descripcion del producto"
                                value="{{ $product->description }}" />
                        </div>

                        <div class="sm:col-span-2">
                            <flux:input label="Stock" name="stock" placeholder="Stock del producto"
                                value="{{ $product->stock }}" readonly />
                        </div>

                        <div class="sm:col-span-1">
                            <flux:input label="Precio de venta" name="price"
                                placeholder="Precio de venta del producto" value="{{ $product->price }}">
                                <x-slot name="iconTrailing">
                                    Bs
                                </x-slot>
                            </flux:input>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">Guardar Producto</flux:button>
                    </div>
                </div>
            </flux:fieldset>
        </form>
    </flux:card>
</x-layouts::app>
