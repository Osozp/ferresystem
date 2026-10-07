<x-layouts::app>
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <flux:fieldset>
            <flux:legend>Creacion de nueva Categoria</flux:legend>
            <div class="space-y-6">
                <flux:input label="Nombre de la categoria" name="name" placeholder="Nombre de la nueva categoria" />

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">Guardar Categoria</flux:button>
                </div>
            </div>
        </flux:fieldset>

    </form>
</x-layouts::app>
