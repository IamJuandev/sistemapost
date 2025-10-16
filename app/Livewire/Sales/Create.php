<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $customers = [];
    public $products = [];
    public $searchProduct = '';
    public $searchResults;
    public $selectedCustomer = '';
    public $selectedProduct = '';
    public $quantity = 1;
    public $cart = [];
    public $payment_method = 'cash';
    public $discount = 0;
    public $invoice_prefix = '';
    public $invoice_number = '';
    public $cufe = '';
    public $subtotal = 0;
    public $tax_amount = 0;
    public $discount_amount = 0;
    public $total_amount = 0;
    public $currency = 'COP';
    public $qr_url = '';
    public $sale_type = 'CONTADO'; // CONTADO o CREDITO
    public $uuid = '';
    public $due_date;
    public $sale_date;
    public $notes = '';
    
    protected $rules = [
        'selectedCustomer' => 'required|exists:customers,id',
        'selectedProduct' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->customers = Customer::all();
        $this->products = Product::where('stock', '>', 0)->get();
        $this->searchResults = collect();
        $this->sale_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d'); // Fecha de vencimiento predeterminada (30 días después)
        $this->currency = 'COP'; // Peso colombiano por defecto
        $this->sale_type = 'CONTADO'; // Tipo de venta por defecto
    }
    
    public function updatedSelectedCustomer()
    {
        // Actualizar productos disponibles cada vez que cambia el cliente
        $this->products = Product::where('stock', '>', 0)->get();
    }
    
    public function updatedSearchProduct()
    {
        // Verificar si el texto ingresado es un código de barras o QR (número de cualquier longitud)
        if (preg_match('/^\d+$/', $this->searchProduct) && strlen($this->searchProduct) >= 8) {
            $product = Product::where('barcode', $this->searchProduct)
                ->where('stock', '>', 0)
                ->first();
                
            if ($product) {
                // Producto encontrado por código de barras o QR, se agrega directamente al carrito
                $this->selectedProduct = $product->id;
                $this->addToCart();
                $this->searchProduct = ''; // Limpiar campo
                $this->searchResults = collect(); // Limpiar resultados
            } else {
                // Código de barras o QR no encontrado, mostrar mensaje de error
                $this->addError('searchProduct', 'Producto no encontrado con este código');
            }
        } else {
            // Búsqueda normal por nombre
            if (strlen($this->searchProduct) > 2) { // Empezar a buscar después de 3 caracteres
                $this->searchResults = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                    ->where('stock', '>', 0)
                    ->limit(10)
                    ->get();
            } else {
                $this->searchResults = collect();
            }
        }
    }
    
    public function selectProduct($productId)
    {
        $this->selectedProduct = $productId;
        $this->searchProduct = '';
        $this->searchResults = collect();
        $this->addToCart();
        
        // Foco de nuevo en el campo de búsqueda después de agregar el producto
        $this->dispatch('focus-search-field');
    }

    public function render()
    {
        $total = collect($this->cart)->sum('subtotal');
        $totalWithDiscount = $total - ($total * ($this->discount / 100));
        $discount_amount = $total * ($this->discount / 100);
        
        // Calcular subtotal e impuestos
        $subtotal = $totalWithDiscount; // Puede ajustarse según lógica de negocio
        $tax_amount = collect($this->cart)->sum(function($item) {
            return $item['subtotal'] * 0.19; // Suponiendo 19% de IVA
        });
        $total_amount = $subtotal + $tax_amount;
        
        return view('livewire.sales.create', [
            'total' => $total,
            'totalWithDiscount' => $totalWithDiscount,
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'discount_amount' => $discount_amount,
            'total_amount' => $total_amount,
        ]);
    }

    public function addToCart()
    {
        $this->validate();
        
        $product = Product::find($this->selectedProduct);
        
        if (!$product) {
            $this->addError('selectedProduct', 'Producto no encontrado');
            return;
        }
        
        if ($product->stock < $this->quantity) {
            $this->addError('quantity', 'Stock insuficiente. Solo hay ' . $product->stock . ' disponibles.');
            return;
        }
        
        $productId = $product->id;
        
        // Calcular montos de línea
        $line_extension_amount = $product->selling_price * $this->quantity;
        $tax_percent = 19.00; // Suponiendo 19% de IVA
        $tax_amount = $line_extension_amount * ($tax_percent / 100);
        $total_line_amount = $line_extension_amount + $tax_amount;
        
        if (isset($this->cart[$productId])) {
            // If product already in cart, update quantity
            $newQuantity = $this->cart[$productId]['quantity'] + $this->quantity;
            
            if ($product->stock < $newQuantity) {
                $this->addError('quantity', 'Stock insuficiente. Solo hay ' . $product->stock . ' disponibles. Ya tienes ' . $this->cart[$productId]['quantity'] . ' en el carrito.');
                return;
            }
            
            $this->cart[$productId]['quantity'] = $newQuantity;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['selling_price'] * $newQuantity;
            
            // Recalcular montos de línea
            $line_extension_amount = $this->cart[$productId]['selling_price'] * $newQuantity;
            $tax_amount = $line_extension_amount * ($tax_percent / 100);
            $this->cart[$productId]['total_line_amount'] = $line_extension_amount + $tax_amount;
        } else {
            // Add new product to cart
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'product_code' => $product->barcode,
                'unit_code' => 'EA', // Unidad por defecto
                'selling_price' => $product->selling_price,
                'quantity' => $this->quantity,
                'subtotal' => $product->selling_price * $this->quantity,
                'line_extension_amount' => $line_extension_amount,
                'tax_percent' => $tax_percent,
                'tax_amount' => $tax_amount,
                'total_line_amount' => $total_line_amount,
            ];
        }
        
        // Reset form fields
        $this->selectedProduct = '';
        $this->quantity = 1;
        
        // Refresh product list (to reflect updated stock)
        $this->products = Product::where('stock', '>', 0)->get();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        
        // Refresh product list (to reflect updated stock)
        $this->products = Product::where('stock', '>', 0)->get();
    }

    public function updateQuantity($productId, $newQuantity)
    {
        if ($newQuantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }
        
        $product = Product::find($productId);
        
        if ($product && $product->stock >= $newQuantity) {
            $this->cart[$productId]['quantity'] = $newQuantity;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['selling_price'] * $newQuantity;
            
            // Recalcular montos de línea
            $line_extension_amount = $this->cart[$productId]['selling_price'] * $newQuantity;
            $tax_amount = $line_extension_amount * ($this->cart[$productId]['tax_percent'] / 100);
            $this->cart[$productId]['total_line_amount'] = $line_extension_amount + $tax_amount;
        } else {
            $this->addError('quantity', 'Stock insuficiente. Solo hay ' . $product->stock . ' disponibles.');
        }
    }

    public function completeSale()
    {
        $this->validate([
            'selectedCustomer' => 'required|exists:customers,id',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,other',
        ]);

        if (empty($this->cart)) {
            $this->addError('cart', 'El carrito está vacío');
            return;
        }

        $total = collect($this->cart)->sum('subtotal');
        $discount_amount = $total * ($this->discount / 100);
        $totalWithDiscount = $total - $discount_amount;

        // Calcular subtotal e impuestos
        $subtotal = $totalWithDiscount;
        $tax_amount = collect($this->cart)->sum(function($item) {
            $line_extension_amount = $item['selling_price'] * $item['quantity'];
            return $line_extension_amount * 0.19; // Suponiendo 19% de IVA
        });
        $total_amount = $subtotal + $tax_amount;

        // Crear la venta
        $sale = Sale::create([
            'customer_id' => $this->selectedCustomer,
            'user_id' => Auth::id(),
            'invoice_prefix' => $this->invoice_prefix,
            'invoice_number' => $this->invoice_number,
            'cufe' => $this->cufe,
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'discount_amount' => $discount_amount,
            'total_amount' => $total_amount,
            'currency' => $this->currency,
            'qr_url' => $this->qr_url,
            'sale_type' => $this->sale_type,
            'uuid' => $this->uuid,
            'sale_date' => $this->sale_date,
            'due_date' => $this->due_date,
            'payment_method' => $this->payment_method,
            'status' => 'completed',
            'notes' => '', // Campo opcional para notas de la venta
        ]);

        // Crear los ítems de la venta y actualizar el stock de productos
        foreach ($this->cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'line_id' => count($this->cart), // Este debería ser el número de línea real
                'description' => $item['description'],
                'product_code' => $item['product_code'],
                'unit_code' => $item['unit_code'],
                'quantity' => $item['quantity'],
                'selling_price' => $item['selling_price'],
                'line_extension_amount' => $item['line_extension_amount'],
                'tax_percent' => $item['tax_percent'],
                'tax_amount' => $item['tax_amount'],
                'total_line_amount' => $item['total_line_amount'],
                'subtotal' => $item['line_extension_amount'], // Agregar el campo subtotal
            ]);
            
            // Actualizar el stock del producto
            $product = Product::find($item['id']);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Resetear el formulario
        $this->reset([
            'cart', 
            'selectedCustomer', 
            'discount',
            'invoice_prefix',
            'invoice_number',
            'cufe',
            'subtotal',
            'tax_amount',
            'discount_amount',
            'total_amount',
            'currency',
            'qr_url',
            'sale_type',
            'uuid',
            'due_date',
            'sale_date'
        ]);
        $this->customers = Customer::all();
        $this->products = Product::where('stock', '>', 0)->get();
        $this->sale_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        
        session()->flash('message', 'Venta completada exitosamente.');
    }

    public function cancelSale()
    {
        $this->reset([
            'cart', 
            'selectedCustomer', 
            'discount',
            'invoice_prefix',
            'invoice_number',
            'cufe',
            'subtotal',
            'tax_amount',
            'discount_amount',
            'total_amount',
            'currency',
            'qr_url',
            'sale_type',
            'uuid',
            'due_date',
            'sale_date'
        ]);
        $this->customers = Customer::all();
        $this->products = Product::where('stock', '>', 0)->get();
        $this->sale_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        
        session()->flash('message', 'Venta cancelada.');
    }
}
