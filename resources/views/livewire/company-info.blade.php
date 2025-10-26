<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">Información de la Empresa</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-300">Gestiona la información básica de tu empresa.</p>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4 rounded-lg shadow-md"
            role="alert">
            <p class="font-bold">Éxito</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-form.input name="company_name" label="Nombre de la Empresa" 
                        wire:model="company_name" required />
                </div>
                <div>
                    <x-form.input name="trade_name" label="Nombre Comercial" 
                        wire:model="trade_name" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-form.input name="nit" label="NIT" 
                            wire:model="nit" required />
                    </div>
                    <div>
                        <x-form.input name="dv" label="DV" 
                            wire:model="dv" required maxlength="1" />
                    </div>
                </div>
                <div>
                    <x-form.input name="address" label="Dirección" 
                        wire:model="address" required />
                </div>
                <div>
                    <x-form.input name="city" label="Ciudad" 
                        wire:model="city" required />
                </div>
                <div>
                    <x-form.input name="department" label="Departamento" 
                        wire:model="department" required />
                </div>
                <div>
                    <x-form.input name="country" label="País" 
                        wire:model="country" required />
                </div>
                <div>
                    <x-form.input name="postal_code" label="Código Postal" 
                        wire:model="postal_code" required />
                </div>
                <div>
                    <x-form.input name="phone" label="Teléfono" 
                        wire:model="phone" required />
                </div>
                <div>
                    <x-form.input name="email" label="Email" 
                        wire:model="email" required type="email" />
                </div>
                <div>
                    <x-form.input name="economic_activity_code" label="Código de Actividad Económica" 
                        wire:model="economic_activity_code" required />
                </div>
                <div>
                    <x-form.input name="regime_type" label="Tipo de Régimen" 
                        wire:model="regime_type" required />
                </div>
                <div>
                    <x-form.input name="software_id" label="ID de Software (Opcional)" 
                        wire:model="software_id" />
                </div>
                <div>
                    <x-form.input name="software_pin" label="PIN de Software (Opcional)" 
                        wire:model="software_pin" />
                </div>
                <div>
                    <x-form.input name="test_set_id" label="ID de Conjunto de Prueba (Opcional)" 
                        wire:model="test_set_id" />
                </div>
                <div>
                    <x-form.select name="environment" label="Ambiente" 
                        :options="['PRODUCCION' => 'Producción', 'PRUEBAS' => 'Pruebas']"
                        wire:model="environment" required />
                </div>
                <div>
                    <x-form.input name="logo_url" label="URL del Logo (Opcional)" 
                        wire:model="logo_url" type="url" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                        Responsabilidades Tributarias
                    </label>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @php
                            $responsibilities = [
                                'R-99-PN' => 'No responsable de IVA',
                                'R-99-NO' => 'No responsable de impuestos',
                                'R-99-OT' => 'Otro régimen',
                                'O-13' => 'Gran contribuyente',
                                'O-23' => 'Autorretenedor',
                                'O-47' => 'Agente de retención IVA',
                                'O-15' => 'Régimen simple de tributación'
                            ];
                        @endphp
                        @foreach($responsibilities as $key => $label)
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="tax_{{ $key }}" 
                                wire:model="tax_responsibilities" 
                                value="{{ $key }}"
                                class="h-4 w-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500"
                            >
                            <label for="tax_{{ $key }}" class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                                {{ $label }} ({{ $key }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button 
                    type="submit"
                    class="h-11 inline-flex items-center justify-center gap-2 px-6 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 transition"
                >
                    Guardar Información
                </button>
            </div>
        </form>
    </div>
</div>