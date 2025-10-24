<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nombre</label>
            <input type="text" id="name" wire:model="name"
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="barcode" class="block text-sm font-medium text-slate-700">Código de Barras</label>
            <input type="text" id="barcode" wire:model="barcode"
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('barcode') border-red-500 @enderror">
            @error('barcode') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-700">Categoría</label>
            <select id="category_id" wire:model="category_id"
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('category_id') border-red-500 @enderror">
                <option value="">Seleccionar Categoría</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="supplier_id" class="block text-sm font-medium text-slate-700">Proveedor</label>
            <select id="supplier_id" wire:model="supplier_id"
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('supplier_id') border-red-500 @enderror">
                <option value="">Seleccionar Proveedor</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                @endforeach
            </select>
            @error('supplier_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="stock" class="block text-sm font-medium text-slate-700">Stock Inicial</label>
            <input type="number" id="stock" wire:model="stock"
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('stock') border-red-500 @enderror">
            @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="cost_price" class="block text-sm font-medium text-slate-700">Precio de
                    Costo</label>
                <input type="number" step="0.01" id="cost_price" wire:model.live="cost_price"
                    class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cost_price') border-red-500 @enderror">
                @error('cost_price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="profit_margin" class="block text-sm font-medium text-slate-700">Margen
                    (%)</label>
                <input type="number" step="0.01" id="profit_margin" wire:model.live="profit_margin"
                    class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('profit_margin') border-red-500 @enderror">
                @error('profit_margin') <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div>
            <label for="tax_rate" class="block text-sm font-medium text-slate-700">Impuesto (%)</label>
            <input type="number" step="0.01" id="tax_rate" wire:model.live="tax_rate" 
                @if($mode === 'edit') readonly @endif
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tax_rate') border-red-500 @enderror 
                @if($mode === 'edit') bg-slate-100 cursor-not-allowed @endif">
            @if($mode === 'edit')
                <small class="text-slate-500">El impuesto se actualiza automáticamente desde las compras</small>
            @endif
            @error('tax_rate') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="selling_price" class="block text-sm font-medium text-slate-700">Precio de Venta
                (Calculado)</label>
            <input type="text" id="selling_price" wire:model="selling_price" readonly
                class="mt-1 block w-full bg-slate-100 border-slate-300 rounded-lg shadow-sm focus:ring-0 cursor-not-allowed">
        </div>
        <div>
            <label for="image_url" class="block text-sm font-medium text-slate-700">URL de Imagen</label>
            <input type="text" id="image_url" wire:model="image_url"
                class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('image_url') border-red-500 @enderror">
            @error('image_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="unit_md" class="block text-sm font-medium text-slate-700">Unidad de Medida</label>
            <input type="text" id="unit_md" wire:model="unit_md" readonly
                class="mt-1 block w-full bg-slate-100 border-slate-300 rounded-lg shadow-sm focus:ring-0 cursor-not-allowed">
        </div>
    </div>
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-slate-700">Descripción</label>
        <textarea id="description" wire:model="description" rows="4"
            class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror"></textarea>
        @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>
</div>