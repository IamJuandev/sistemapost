<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $suppliers = [];
    public $products = [];
    public $selectedSupplier = '';
    public $selectedProduct = '';
    public $quantity = 1;
    public $unit_price = 0;
    public $tax_percent = 0;
    public $invoice_prefix = '';
    public $invoice_number = '';
    public $supplier_nit = '';
    public $supplier_name = '';
    public $subtotal = 0;
    public $tax_amount = 0;
    public $withholding_amount = 0;
    public $total_with_tax = 0;
    public $currency = 'COP';
    public $authorization_number = '';
    public $authorization_expiration;
    public $qr_url = '';
    public $purchase_type = '';
    public $notes = '';
    public $due_date;
    public $purchase_date;
    public $cart = [];

    protected $rules = [
        'selectedSupplier' => 'required|exists:suppliers,id',
        'invoice_prefix' => 'nullable|string|max:20',
        'invoice_number' => 'required|string|max:255',
        'selectedProduct' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
        'tax_percent' => 'required|numeric|min:0|max:100',
    ];

    public function mount()
    {
        $this->suppliers = Supplier::all();
        $this->products = Product::all();
        $this->purchase_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->currency = 'COP';
        $this->purchase_type = 'purchase';
    }
    
    public function updatedSelectedSupplier()
    {
        // Actualizar productos disponibles filtrados por el proveedor seleccionado
        if ($this->selectedSupplier) {
            $this->products = Product::where('supplier_id', $this->selectedSupplier)->get();

            // Generar el prefijo y el número de factura
            $supplier = Supplier::find($this->selectedSupplier);
            if ($supplier) {
                $this->invoice_prefix = $supplier->invoice_prefix;
                $this->invoice_number = $this->generateInvoiceNumber();
            }
        } else {
            $this->products = Product::all();
        }
    }

    public function updatedSelectedProduct()
    {
        // Cuando se selecciona un producto, se pueden autocompletar valores predeterminados
        if ($this->selectedProduct) {
            $product = Product::find($this->selectedProduct);
            if ($product) {
                // Si el campo de precio unitario está en 0, usar el precio de costo del producto
                if ($this->unit_price == 0 || $this->unit_price == '') {
                    $this->unit_price = $product->cost_price;
                }
                
                // Si el campo de porcentaje de impuestos está en 0, usar el valor del producto
                if ($this->tax_percent == 0 || $this->tax_percent == '') {
                    $this->tax_percent = $product->tax_rate;
                }
            }
        }
    }

    private function generateInvoiceNumber()
    {
        // Lógica para generar el número de factura
        return 'FV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $subtotal = collect($this->cart)->sum('line_extension_amount');
        $tax_amount = collect($this->cart)->sum('tax_amount');
        $total_with_tax = $subtotal + $tax_amount;
        
        // Actualizar las propiedades públicas para que se reflejen en el formulario
        $this->subtotal = $subtotal;
        $this->tax_amount = $tax_amount;
        $this->total_with_tax = $total_with_tax;
        
        return view('livewire.purchases.create', [
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'total_with_tax' => $total_with_tax,
        ]);
    }

    public function addToCart()
    {
        $this->validate([
            'selectedProduct' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'tax_percent' => 'required|numeric|min:0|max:100',
        ]);
        
        $product = Product::find($this->selectedProduct);
        
        if (!$product) {
            $this->addError('selectedProduct', 'Producto no encontrado');
            return;
        }
        
        $productId = $product->id;
        
        // Calculate line amounts with discount consideration
        $line_extension_amount = $this->unit_price * $this->quantity;
        $discount = 0; // Temporalmente sin descuento, se puede implementar una funcionalidad para agregar descuentos
        $subtotal_after_discount = $line_extension_amount - $discount;
        $tax_amount = $subtotal_after_discount * ($this->tax_percent / 100);
        $total_line_amount = $subtotal_after_discount + $tax_amount;
        
        if (isset($this->cart[$productId])) {
            // If product already in cart, update quantity
            $this->cart[$productId]['quantity'] += $this->quantity;
            $this->cart[$productId]['line_extension_amount'] = $this->cart[$productId]['unit_price'] * $this->cart[$productId]['quantity'];
            $this->cart[$productId]['discount'] = 0; // Actualizar si se implementa descuento
            $this->cart[$productId]['subtotal_after_discount'] = $this->cart[$productId]['line_extension_amount'] - $this->cart[$productId]['discount'];
            $this->cart[$productId]['tax_amount'] = $this->cart[$productId]['subtotal_after_discount'] * ($this->cart[$productId]['tax_percent'] / 100);
            $this->cart[$productId]['total_line_amount'] = $this->cart[$productId]['subtotal_after_discount'] + $this->cart[$productId]['tax_amount'];
        } else {
            // Add new product to cart
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'product_code' => $product->barcode,
                'unit_code' => 'EA', // Unidad por defecto
                'unit_price' => $this->unit_price,
                'tax_percent' => $this->tax_percent,
                'quantity' => $this->quantity,
                'quantity_for_unit' => $product->quantity_for_unit, // Añadir quantity_for_unit si existe
                'line_extension_amount' => $line_extension_amount,
                'discount' => $discount,
                'subtotal_after_discount' => $subtotal_after_discount,
                'tax_amount' => $tax_amount,
                'total_line_amount' => $total_line_amount,
                'total_value' => $total_line_amount, // Total final después de aplicar descuentos e impuestos
                'line_id' => count($this->cart) + 1, // Agregar line_id secuencial
            ];
        }
        
        // Reset form fields for product
        $this->selectedProduct = '';
        $this->quantity = 1;
        $this->unit_price = 0;
        $this->tax_percent = 0;
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function updateQuantity($productId, $newQuantity)
    {
        if ($newQuantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }
        
        $this->cart[$productId]['quantity'] = $newQuantity;
        $line_extension_amount = $this->cart[$productId]['unit_price'] * $newQuantity;
        $subtotal_after_discount = $line_extension_amount - ($this->cart[$productId]['discount'] ?? 0);
        $tax_amount = $subtotal_after_discount * ($this->cart[$productId]['tax_percent'] / 100);
        $total_line_amount = $subtotal_after_discount + $tax_amount;
        $this->cart[$productId]['line_extension_amount'] = $line_extension_amount;
        $this->cart[$productId]['subtotal_after_discount'] = $subtotal_after_discount;
        $this->cart[$productId]['tax_amount'] = $tax_amount;
        $this->cart[$productId]['total_line_amount'] = $total_line_amount;
        $this->cart[$productId]['total_value'] = $total_line_amount; // Actualizar el valor total
    }

    public function completePurchase()
    {
        $this->validate([
            'selectedSupplier' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|max:255',
        ]);

        if (empty($this->cart)) {
            $this->addError('cart', 'El carrito está vacío');
            return;
        }

        // Calcular totales
        $subtotal = collect($this->cart)->sum('line_extension_amount');
        $tax_amount = collect($this->cart)->sum('tax_amount');
        $total_with_tax = $subtotal + $tax_amount;

        // Obtener información del proveedor
        $supplier = Supplier::find($this->selectedSupplier);

        // Crear la compra
        $purchase = Purchase::create([
            'supplier_id' => $this->selectedSupplier,
            'invoice_prefix' => $this->invoice_prefix,
            'invoice_number' => $this->invoice_number,
            'supplier_nit' => $supplier->nit,
            'supplier_name' => $supplier->company_name,
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'withholding_amount' => $this->withholding_amount,
            'total_with_tax' => $total_with_tax,
            'currency' => $this->currency,
            'authorization_number' => $this->authorization_number,
            'authorization_expiration' => $this->authorization_expiration,
            'qr_url' => $this->qr_url,
            'purchase_type' => $this->purchase_type,
            'purchase_date' => $this->purchase_date,
            'due_date' => $this->due_date,
            'status' => 'completed',
            'notes' => $this->notes,
        ]);

        // Crear los items de la compra
        foreach ($this->cart as $productId => $item) {
            PurchaseItem::create([
                'invoice_number' => $this->invoice_number,
                'product_id' => $productId,
                'line_id' => $item['line_id'] ?? 1,
                'description' => $item['description'] ?? '',
                'product_code' => $item['product_code'] ?? '',
                'unit_code' => $item['unit_code'] ?? 'EA',
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'quantity_for_unit' => $item['quantity_for_unit'] ?? null, // Añadir quantity_for_unit
                'item_subtotal' => $item['line_extension_amount'], // Subtotal por ítem antes de descuentos
                'line_extension_amount' => $item['line_extension_amount'] - ($item['discount'] ?? 0), // Importe de línea después de descuentos
                'discount' => $item['discount'] ?? 0, // Campo de descuento
                'tax_percent' => $item['tax_percent'],
                'tax_amount' => $item['tax_amount'],
                'total_line_amount' => $item['total_line_amount'],
                'total_value' => $item['total_value'], // Campo de valor total
            ]);
        }

        // Actualizar stock de productos y unidad de medida (unit_md)
        foreach ($this->cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                // Calcular la cantidad total a agregar al stock
                // quantity representa el número de cajas (unidades de empaque)
                // quantity_for_unit representa cuántas unidades vienen en cada caja
                $totalQuantity = $item['quantity'] * ($item['quantity_for_unit'] ?: 1);
                
                $product->stock += $totalQuantity; // Aumentar stock con la cantidad total comprada
                // Actualizar el campo unit_md con la concatenación de unit_code y quantity
                $product->unit_md = $item['unit_code'] . ' x ' . $item['quantity'];
                
                // Si la unidad de medida es CJ (caja) y quantity_for_unit > 1, calcular precio unitario
                $effectiveUnitPriceWithoutTax = $item['unit_price'];
                if (strtolower($item['unit_code']) === 'cj' && $item['quantity_for_unit'] && $item['quantity_for_unit'] > 1) {
                    $effectiveUnitPriceWithoutTax = $item['unit_price'] / $item['quantity_for_unit'];
                }
                
                // Calcular precio con IVA incluido (precio de costo final)
                $taxRateDecimal = $item['tax_percent'] / 100;
                $effectiveUnitPriceWithTax = $effectiveUnitPriceWithoutTax * (1 + $taxRateDecimal);
                
                // Actualizar precios del producto
                $product->cost_price = $effectiveUnitPriceWithTax;
                
                // Calcular precio de venta con margen de ganancia
                if ($product->profit_margin) {
                    $profitDecimal = $product->profit_margin / 100;
                    $product->selling_price = $effectiveUnitPriceWithTax * (1 + $profitDecimal);
                } else {
                    $product->selling_price = $effectiveUnitPriceWithTax;
                }
                
                $product->tax_rate = $item['tax_percent']; // Actualizar el porcentaje de impuesto
                $product->quantity_for_unit = $item['quantity_for_unit']; // Actualizar quantity_for_unit si está disponible
                $product->save();
            }
        }

        // Limpiar el carrito y reiniciar el formulario
        $this->cart = [];
        $this->selectedSupplier = '';
        $this->invoice_prefix = '';
        $this->invoice_number = '';
        $this->notes = '';
        
        session()->flash('message', 'Compra registrada exitosamente.');
    }

    public function cancelPurchase()
    {
        // Limpiar el carrito y reiniciar el formulario
        $this->cart = [];
        $this->selectedSupplier = '';
        $this->invoice_prefix = '';
        $this->invoice_number = '';
        $this->notes = '';
    }
}