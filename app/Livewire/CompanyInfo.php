<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CompanyInfo as CompanyModel;
use Illuminate\Support\Facades\DB;

class CompanyInfo extends Component
{
    public $company;
    public $company_name;
    public $trade_name;
    public $nit;
    public $dv;
    public $address;
    public $city;
    public $department;
    public $country;
    public $postal_code;
    public $phone;
    public $email;
    public $economic_activity_code;
    public $regime_type;
    public $tax_responsibilities = [];
    public $software_id;
    public $software_pin;
    public $test_set_id;
    public $environment = 'PRUEBAS';
    public $logo_url;

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'trade_name' => 'required|string|max:255',
        'nit' => 'required|string|max:20',
        'dv' => 'required|string|max:1',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'department' => 'required|string|max:100',
        'country' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'economic_activity_code' => 'required|string|max:20',
        'regime_type' => 'required|string|max:50',
        'tax_responsibilities' => 'array',
        'tax_responsibilities.*' => 'string',
        'software_id' => 'nullable|string|max:100',
        'software_pin' => 'nullable|string|max:100',
        'test_set_id' => 'nullable|string|max:100',
        'environment' => 'required|in:PRODUCCION,PRUEBAS',
        'logo_url' => 'nullable|url|max:500',
    ];

    public function mount()
    {
        $this->loadCompanyData();
    }

    private function loadCompanyData()
    {
        $this->company = CompanyModel::first();
        if ($this->company) {
            $this->company_name = $this->company->company_name;
            $this->trade_name = $this->company->trade_name;
            $this->nit = $this->company->nit;
            $this->dv = $this->company->dv;
            $this->address = $this->company->address;
            $this->city = $this->company->city;
            $this->department = $this->company->department;
            $this->country = $this->company->country;
            $this->postal_code = $this->company->postal_code;
            $this->phone = $this->company->phone;
            $this->email = $this->company->email;
            $this->economic_activity_code = $this->company->economic_activity_code;
            $this->regime_type = $this->company->regime_type;
            $this->tax_responsibilities = $this->company->tax_responsibilities ?? [];
            $this->software_id = $this->company->software_id;
            $this->software_pin = $this->company->software_pin;
            $this->test_set_id = $this->company->test_set_id;
            $this->environment = $this->company->environment;
            $this->logo_url = $this->company->logo_url;
        } else {
            // Valores por defecto
            $this->country = 'Colombia';
            $this->environment = 'PRUEBAS';
        }
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->company) {
                // Actualizar la información existente
                $this->company->update([
                    'company_name' => $this->company_name,
                    'trade_name' => $this->trade_name,
                    'nit' => $this->nit,
                    'dv' => $this->dv,
                    'address' => $this->address,
                    'city' => $this->city,
                    'department' => $this->department,
                    'country' => $this->country,
                    'postal_code' => $this->postal_code,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'economic_activity_code' => $this->economic_activity_code,
                    'regime_type' => $this->regime_type,
                    'tax_responsibilities' => $this->tax_responsibilities,
                    'software_id' => $this->software_id,
                    'software_pin' => $this->software_pin,
                    'test_set_id' => $this->test_set_id,
                    'environment' => $this->environment,
                    'logo_url' => $this->logo_url,
                ]);
            } else {
                // Crear nueva información de empresa
                CompanyModel::create([
                    'company_name' => $this->company_name,
                    'trade_name' => $this->trade_name,
                    'nit' => $this->nit,
                    'dv' => $this->dv,
                    'address' => $this->address,
                    'city' => $this->city,
                    'department' => $this->department,
                    'country' => $this->country,
                    'postal_code' => $this->postal_code,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'economic_activity_code' => $this->economic_activity_code,
                    'regime_type' => $this->regime_type,
                    'tax_responsibilities' => $this->tax_responsibilities,
                    'software_id' => $this->software_id,
                    'software_pin' => $this->software_pin,
                    'test_set_id' => $this->test_set_id,
                    'environment' => $this->environment,
                    'logo_url' => $this->logo_url,
                ]);
            }
        });

        $this->loadCompanyData();
        session()->flash('message', 'Información de la empresa actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.company-info');
    }
}
