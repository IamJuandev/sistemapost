<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;

class InvoiceView extends Component
{
    public Purchase $invoice;

    public function mount(Purchase $purchase)
    {
        $this->invoice = $purchase->load(['supplier', 'purchaseItems']);
    }

    public function render(): View
    {
        return view('livewire.purchases.invoice-view');
    }

    public function markAsPaid($invoiceId)
    {
        $invoice = Purchase::find($invoiceId);
        if ($invoice) {
            // Actualizar el estado de la factura
            $invoice->update(['status' => 'completed']);

            // Recorrer cada ítem de la factura para actualizar el stock y precios
            foreach ($invoice->purchaseItems as $item) {
                $product = $item->product;

                if ($product) {
                    // Calcular la cantidad total a agregar al stock
                    // quantity representa el número de cajas (unidades de empaque)
                    // quantity_for_unit representa cuántas unidades vienen en cada caja
                    $totalQuantity = $item->quantity * ($item->quantity_for_unit ?: 1);

                    // Actualizar el stock del producto
                    $product->stock += $totalQuantity;

                    // Actualizar la cantidad por unidad en el producto
                    if ($item->quantity_for_unit) {
                        $product->quantity_for_unit = $item->quantity_for_unit;
                    }

                    // Calcular precio sin IVA (precio unitario)
                    $costWithoutTax = $item->unit_price;

                    // Si la unidad de medida es CJ (caja) y quantity_for_unit > 1, calcular precio unitario
                    if (strtolower($item->unit_code) === 'cj' && $item->quantity_for_unit && $item->quantity_for_unit > 1) {
                        $costWithoutTax = $item->unit_price / $item->quantity_for_unit;
                    }

                    // Calcular precio con IVA incluido (precio de costo final)
                    $taxRateDecimal = $item->tax_percent / 100;
                    $costWithTax = $costWithoutTax * (1 + $taxRateDecimal);

                    // Actualizar precio de costo
                    $product->cost_price = $costWithTax;

                    // Calcular precio de venta solo con margen de ganancia (sin incluir IVA nuevamente)
                    // Asumiendo que el producto ya tiene un margen de ganancia definido
                    if ($product->profit_margin) {
                        $profitDecimal = $product->profit_margin / 100;
                        $product->selling_price = $costWithTax * (1 + $profitDecimal);
                    } else {
                        // Si no hay margen definido, usar el costo como precio de venta
                        $product->selling_price = $costWithTax;
                    }

                    $product->tax_rate = $item->tax_percent; // Actualizar el porcentaje de impuesto
                    $product->save();
                } else {
                    // Si el producto no existe, crearlo solo si no existe otro con el mismo nombre y proveedor
                    $existingProduct = \App\Models\Product::where('name', $item->description)
                        ->where('supplier_id', $invoice->supplier_id)
                        ->first();

                    if (!$existingProduct) {
                        // Calcular la cantidad total para el nuevo producto
                        // quantity representa el número de cajas (unidades de empaque)
                        // quantity_for_unit representa cuántas unidades vienen en cada caja
                        $totalQuantity = $item->quantity * ($item->quantity_for_unit ?: 1);

                        // Calcular precio sin IVA (precio unitario)
                        $costWithoutTax = $item->unit_price;

                        // Si la unidad de medida es CJ (caja) y quantity_for_unit > 1, calcular precio unitario
                        if (strtolower($item->unit_code) === 'cj' && $item->quantity_for_unit && $item->quantity_for_unit > 1) {
                            $costWithoutTax = $item->unit_price / $item->quantity_for_unit;
                        }

                        // Calcular precio con IVA incluido (precio de costo final)
                        $taxRateDecimal = $item->tax_percent / 100;
                        $costWithTax = $costWithoutTax * (1 + $taxRateDecimal);

                        // Calcular precio de venta con margen de ganancia (sin incluir IVA nuevamente)
                        $defaultProfitMargin = 0.20; // 20% de margen por defecto
                        $sellingPrice = $costWithTax * (1 + $defaultProfitMargin);

                        // Encontrar una categoría existente o usar una por defecto
                        $defaultCategory = \App\Models\Category::first(); // Tomar la primera categoría existente
                        if (!$defaultCategory) {
                            // Si no hay categorías, crear una por defecto
                            $defaultCategory = \App\Models\Category::create([
                                'name' => 'Sin Categoría',
                                'description' => 'Categoría por defecto para productos nuevos'
                            ]);
                        }

                        \App\Models\Product::create([
                            'name' => $item->description ?: 'Producto desde compra #' . $invoice->invoice_number,
                            'barcode' => null, // Dejar código de barras en blanco
                            'cost_price' => $costWithTax,
                            'tax_rate' => $item->tax_percent,
                            'profit_margin' => $defaultProfitMargin * 100, // Convertir a porcentaje
                            'selling_price' => $sellingPrice,
                            'stock' => $totalQuantity,
                            'category_id' => $defaultCategory->id,
                            'supplier_id' => $invoice->supplier_id,
                            'quantity_for_unit' => $item->quantity_for_unit, // Agregar el campo quantity_for_unit
                        ]);
                    } else {
                        // Si el producto ya existe, actualizar su stock y precios
                        $totalQuantity = $item->quantity * ($item->quantity_for_unit ?: 1);

                        // Calcular precio sin IVA (precio unitario)
                        $costWithoutTax = $item->unit_price;

                        // Si la unidad de medida es CJ (caja) y quantity_for_unit > 1, calcular precio unitario
                        if (strtolower($item->unit_code) === 'cj' && $item->quantity_for_unit && $item->quantity_for_unit > 1) {
                            $costWithoutTax = $item->unit_price / $item->quantity_for_unit;
                        }

                        // Calcular precio con IVA incluido (precio de costo final)
                        $taxRateDecimal = $item->tax_percent / 100;
                        $costWithTax = $costWithoutTax * (1 + $taxRateDecimal);

                        $existingProduct->stock += $totalQuantity;
                        $existingProduct->cost_price = $costWithTax;

                        // Calcular precio de venta con margen de ganancia
                        if ($existingProduct->profit_margin) {
                            $profitDecimal = $existingProduct->profit_margin / 100;
                            $existingProduct->selling_price = $costWithTax * (1 + $profitDecimal);
                        } else {
                            $existingProduct->selling_price = $costWithTax;
                        }

                        $existingProduct->tax_rate = $item->tax_percent;
                        $existingProduct->quantity_for_unit = $item->quantity_for_unit;
                        $existingProduct->save();
                    }
                }
            }

            $this->invoice->refresh();
            session()->flash('message', 'Factura marcada como pagada exitosamente y stock actualizado.');
        }
    }

    public function markAsCancelled($invoiceId)
    {
        $invoice = Purchase::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'cancelled']);
            $this->invoice->refresh();
            session()->flash('message', 'Factura cancelada exitosamente.');
        }
    }
}