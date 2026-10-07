<x-layouts::app title="Productos" :breadcrumbs="['admin.products.index' => 'Productos']">

    <section class="relative bg-white shadow-md rounded-lg mb-5">
        <div class="items-center justify-between p-4 flex space-y-0 space-x-4">
            <div>
                <h5 class="mr-3 font-semibold">Crear nuevo producto</h5>
                <p class="text-gray-500">Lista de productos</p>
            </div>
        </div>
    </section>

    <section class="relative bg-white shadow-md rounded-lg mb-5 p-4">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="category" class="block text-sm/6 font-medium text-gray-900">Seleccione Categoria</label>
                    <div class="mt-2 grid grid-cols-1">
                        <select name="category_id" autocomplete="category-name"
                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true"
                            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
                            <path
                                d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="sm:col-span-2 sm:col-start-1">
                    <label for="city" class="block text-sm/6 font-medium text-gray-900">Codigo</label>
                    <div class="mt-2">
                        <input type="text" name="codigo" autocomplete="address-level2"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="region" class="block text-sm/6 font-medium text-gray-900">Descripcion</label>
                    <div class="mt-2">
                        <input name="description"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="postal-code" class="block text-sm/6 font-medium text-gray-900">Stock</label>
                    <div class="mt-2">
                        <input type="text" name="stock" autocomplete="postal-code"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>


            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancelar</button>
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </section>

</x-layouts::app>
