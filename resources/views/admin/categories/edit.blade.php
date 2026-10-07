<x-layouts::app>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <flux:fieldset>
            <flux:legend>Editar Categoría: {{ $category->name }}</flux:legend>
            <div class="space-y-6">
                <flux:input label="Nombre de la categoría" name="name" value="{{ $category->name }}" />

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">Guardar Categoría</flux:button>
                </div>
            </div>

        </flux:fieldset>
    </form>
</x-layouts::app>
