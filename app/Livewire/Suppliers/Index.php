<?php

namespace App\Livewire\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $company_name = '';
    public $agent_name = '';
    public $phone = '';
    public $email = '';
    public $nit = '';
    public $visit_days = [];
    public $selectedSupplier = null;
    public $showCreateForm = false;
    public $showEditForm = false;
    public $selectedDays = [];

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'agent_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'nit' => 'required|string|max:20|unique:suppliers,nit',
        'visit_days' => 'nullable|array',
    ];

    public function render()
    {
        $suppliers = Supplier::where('company_name', 'like', '%' . $this->search . '%')
            ->orWhere('agent_name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function store()
    {
        $this->validate();

        Supplier::create([
            'company_name' => $this->company_name,
            'agent_name' => $this->agent_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'nit' => $this->nit,
            'visit_days' => $this->selectedDays,
        ]);

        $this->resetForm();
        session()->flash('message', 'Proveedor creado exitosamente.');
    }

    public function edit(Supplier $supplier)
    {
        $this->resetForm();
        $this->selectedSupplier = $supplier;
        $this->company_name = $supplier->company_name;
        $this->agent_name = $supplier->agent_name;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->nit = $supplier->nit;
        $this->selectedDays = $supplier->visit_days ?? [];
        $this->showEditForm = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->selectedSupplier) {
            $this->selectedSupplier->update([
                'company_name' => $this->company_name,
                'agent_name' => $this->agent_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'nit' => $this->nit,
                'visit_days' => $this->selectedDays,
            ]);

            $this->resetForm();
            session()->flash('message', 'Proveedor actualizado exitosamente.');
        }
    }

    public function delete(Supplier $supplier)
    {
        $supplier->delete();
        session()->flash('message', 'Proveedor eliminado exitosamente.');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->company_name = '';
        $this->agent_name = '';
        $this->phone = '';
        $this->email = '';
        $this->nit = '';
        $this->selectedDays = [];
        $this->selectedSupplier = null;
        $this->showCreateForm = false;
        $this->showEditForm = false;
        $this->resetErrorBag();
    }
}
