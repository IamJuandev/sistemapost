<x-layouts.app title="Test Form Components">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Form Components Test</h1>
            
            <form class="space-y-6">
                <!-- Input Component Test -->
                <x-form.input 
                    name="name" 
                    label="Nombre" 
                    placeholder="Ingrese su nombre" 
                    required 
                />
                
                <x-form.input 
                    name="email" 
                    label="Email" 
                    type="email" 
                    placeholder="ejemplo@correo.com" 
                />
                
                <!-- Select Component Test -->
                <x-form.select 
                    name="category" 
                    label="Categoría" 
                    :options="[
                        '1' => 'Electrónica',
                        '2' => 'Ropa',
                        '3' => 'Hogar',
                        '4' => 'Deportes'
                    ]" 
                    required 
                />
                
                <!-- Textarea Component Test -->
                <x-form.textarea 
                    name="description" 
                    label="Descripción" 
                    placeholder="Ingrese una descripción detallada" 
                    rows="4"
                />
                
                <!-- Button Component Test -->
                <div class="flex space-x-4">
                    <x-form.button type="submit" variant="primary">
                        Guardar
                    </x-form.button>
                    
                    <x-form.button type="button" variant="secondary">
                        Cancelar
                    </x-form.button>
                    
                    <x-form.button type="button" variant="danger">
                        Eliminar
                    </x-form.button>
                    
                    <x-form.button type="button" variant="success" loading>
                        Cargando...
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>