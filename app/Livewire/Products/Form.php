<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class Form extends Component
{
    public Product $product;
    public $categories;
    public $suppliers;
    public $mode; // 'create' or 'edit'

    public $name;
    public $description;
    public $barcode;
    public $cost_price;
    public $tax_rate;
    public $profit_margin;
    public $selling_price;
    public $stock;
    public $image_url;
    public $category_id;
    public $supplier_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'barcode' => 'required|string|max:255|unique:products,barcode',
        'cost_price' => 'required|numeric',
        'tax_rate' => 'required|numeric|min:0|max:100',
        'profit_margin' => 'required|numeric|min:0|max:100',
        'selling_price' => 'required|numeric',
        'stock' => 'required|integer|min:0',
        'image_url' => 'nullable|url',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
    ];

    public function mount($productId = null)
    {
        $this->categories = Category::all();
        $this->suppliers = Supplier::all();

        if ($productId) {
            $this->product = Product::findOrFail($productId);
            $this->mode = 'edit';
            $this->fillForm();
        } else {
            $this->product = new Product();
            $this->mode = 'create';
            $this->resetForm();
        }
    }

    public function fillForm()
    {
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->barcode = $this->product->barcode;
        $this->cost_price = $this->product->cost_price;
        $this->tax_rate = $this->product->tax_rate;
        $this->profit_margin = $this->product->profit_margin;
        $this->selling_price = $this->product->selling_price;
        $this->stock = $this->product->stock;
        $this->image_url = $this->product->image_url;
        $this->category_id = $this->product->category_id;
        $this->supplier_id = $this->product->supplier_id;
    }

    public function resetForm()
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
    }

    // Update selling price when cost price, tax rate, or profit margin changes
    public function updatedCostPrice()
    {
        $this->calculateSellingPrice();
    }

    public function updatedTaxRate()
    {
        $this->calculateSellingPrice();
    }

    public function updatedProfitMargin()
    {
        $this->calculateSellingPrice();
    }

    private function calculateSellingPrice()
    {
        if ($this->cost_price && $this->tax_rate && $this->profit_margin) {
            $tax = $this->cost_price * ($this->tax_rate / 100);
            $profit = $this->cost_price * ($this->profit_margin / 100);
            $this->selling_price = $this->cost_price + $tax + $profit;
        }
    }

    public function save()
    {
        $rules = $this->rules;
        
        if ($this->mode === 'edit') {
            $rules['barcode'] = 'required|string|max:255|unique:products,barcode,' . $this->product->id;
        }
        
        $this->validate($rules);

        $productData = [
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
        ];

        if ($this->mode === 'create') {
            $this->product = Product::create($productData);
            session()->flash('message', 'Producto creado exitosamente.');
        } else {
            $this->product->update($productData);
            session()->flash('message', 'Producto actualizado exitosamente.');
        }

        return $this->product;
    }

    public function render()
    {
        return view('livewire.products.form');
    }
}