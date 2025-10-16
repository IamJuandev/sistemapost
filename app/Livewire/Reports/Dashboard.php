<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalProducts;
    public $totalCustomers;
    public $totalSuppliers;
    public $totalSales;
    public $totalPurchases;
    public $salesThisMonth;
    public $purchasesThisMonth;
    public $lowStockProducts = [];
    public $recentSales = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function render()
    {
        return view('livewire.reports.dashboard');
    }

    private function loadDashboardData()
    {
        // Totales generales
        $this->totalProducts = Product::count();
        $this->totalCustomers = Customer::count();
        $this->totalSuppliers = Supplier::count();
        $this->totalSales = Sale::count();
        $this->totalPurchases = Purchase::where('status', 'completed')->count(); // Solo compras completadas
        
        // Ventas y compras del mes actual
        $currentMonth = now()->startOfMonth();
        $this->salesThisMonth = Sale::where('sale_date', '>=', $currentMonth)->sum('total_amount');
        $this->purchasesThisMonth = Purchase::where('purchase_date', '>=', $currentMonth)
            ->where('status', 'completed') // Solo compras completadas
            ->sum('total_amount');
        
        // Productos con bajo stock (menos de 10 unidades)
        $this->lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();
        
        // Ventas recientes
        $this->recentSales = Sale::with('customer')
            ->orderBy('sale_date', 'desc')
            ->limit(10)
            ->get();
    }
}