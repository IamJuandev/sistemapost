<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $name = '';
    public $description = '';
    public $barcode = '';
    public $cost_price = '';
    public $tax_rate = '';
    public $profit_margin = '';
    public $selling_price = '';
    public $stock = '';
    public $image_url = '';
    public $category_id = '';
    public $supplier_id = '';
    public $selectedProduct = null;
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'barcode' => 'required|string|max:255|unique:products,barcode',
        'cost_price' => 'required|numeric',
        'tax_rate' => 'nullable|numeric|min:0|max:100',
        'profit_margin' => 'required|numeric|min:0|max:100',
        'selling_price' => 'required|numeric',
        'stock' => 'required|integer|min:0',
        'image_url' => 'nullable|url',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
    ];

    // Update selling price when cost price, tax rate, or profit margin changes
    public function updatedCostPrice()
    {
        $this->calculateSellingPrice();
    }

    public function updatedTaxRate()
    {
        // No hacer nada ya que el impuesto se controla desde compras
        // $this->calculateSellingPrice();
    }

    public function updatedProfitMargin()
    {
        $this->calculateSellingPrice();
    }

    private function calculateSellingPrice()
    {
        if ($this->cost_price && $this->tax_rate && $this->profit_margin) {
            // El precio de costo ya incluye el IVA, así que no se vuelve a aplicar
            $profit = $this->cost_price * ($this->profit_margin / 100);
            $this->selling_price = $this->cost_price + $profit;
        }
    }

    public function render()
    {
        $products = Product::with(['category', 'supplier'])
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateForm = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'barcode' => $this->barcode,
            'cost_price' => $this->cost_price,
            'tax_rate' => $this->tax_rate,
            'profit_margin' => $this->profit_margin,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
            'image_url' => $this->image_url,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
        ]);

        $this->resetForm();
        session()->flash('message', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        $this->resetForm();
        $this->selectedProduct = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->barcode = $product->barcode;
        $this->cost_price = $product->cost_price;
        $this->tax_rate = $product->tax_rate;
        $this->profit_margin = $product->profit_margin;
        $this->selling_price = $product->selling_price;
        $this->stock = $product->stock;
        $this->image_url = $product->image_url;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->showEditForm = true;
        $this->showModal = true;
    }

    public function update()
    {
        $rules = $this->rules;
        // Remove unique rule for barcode during update to exclude the current product
        $rules['barcode'] = 'required|string|max:255|unique:products,barcode,' . $this->selectedProduct->id;
        // Remove tax_rate from validation since it's controlled by purchases
        unset($rules['tax_rate']);
        
        $this->validate($rules);

        if ($this->selectedProduct) {
            $this->selectedProduct->update([
                'name' => $this->name,
                'description' => $this->description,
                'barcode' => $this->barcode,
                'cost_price' => $this->cost_price,
                // Excluir tax_rate ya que se actualiza desde compras
                'profit_margin' => $this->profit_margin,
                'selling_price' => $this->selling_price,
                'stock' => $this->stock,
                'image_url' => $this->image_url,
                'category_id' => $this->category_id,
                'supplier_id' => $this->supplier_id,
            ]);

            $this->resetForm();
            session()->flash('message', 'Producto actualizado exitosamente.');
        }
    }

    public function delete(Product $product)
    {
        $product->delete();
        session()->flash('message', 'Producto eliminado exitosamente.');
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->barcode = '';
        $this->cost_price = '';
        $this->tax_rate = '';
        $this->profit_margin = '';
        $this->selling_price = '';
        $this->stock = '';
        $this->image_url = '';
        $this->category_id = '';
        $this->supplier_id = '';
        $this->selectedProduct = null;
        $this->showCreateForm = false;
        $this->showEditForm = false;
        $this->showModal = false;
        $this->resetErrorBag();
    }
}
