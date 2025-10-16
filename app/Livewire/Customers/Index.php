<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $address = '';
    public $credit_limit = '';
    public $current_debt = '';
    public $selectedCustomer = null;
    public $showCreateForm = false;
    public $showEditForm = false;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'credit_limit' => 'nullable|numeric|min:0',
        'current_debt' => 'nullable|numeric|min:0',
    ];

    public function render()
    {
        $customers = Customer::where('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers
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

        Customer::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'credit_limit' => $this->credit_limit ?: null,
            'current_debt' => $this->current_debt ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Cliente creado exitosamente.');
    }

    public function edit(Customer $customer)
    {
        $this->resetForm();
        $this->selectedCustomer = $customer;
        $this->first_name = $customer->first_name;
        $this->last_name = $customer->last_name;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->credit_limit = $customer->credit_limit;
        $this->current_debt = $customer->current_debt;
        $this->showEditForm = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->selectedCustomer) {
            $this->selectedCustomer->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name ?: null,
                'phone' => $this->phone ?: null,
                'address' => $this->address ?: null,
                'credit_limit' => $this->credit_limit ?: null,
                'current_debt' => $this->current_debt ?: null,
            ]);

            $this->resetForm();
            session()->flash('message', 'Cliente actualizado exitosamente.');
        }
    }

    public function delete(Customer $customer)
    {
        $customer->delete();
        session()->flash('message', 'Cliente eliminado exitosamente.');
    }

    public function cancel()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->phone = '';
        $this->address = '';
        $this->credit_limit = '';
        $this->current_debt = '';
        $this->selectedCustomer = null;
        $this->showCreateForm = false;
        $this->showEditForm = false;
        $this->resetErrorBag();
    }
}
