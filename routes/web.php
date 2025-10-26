<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', \App\Livewire\Reports\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route for testing form components
Route::get('/test-form-components', function () {
    return view('test-form-components');
})->middleware(['auth', 'verified'])->name('test.form.components');

// Rutas para la gestión de inventario
Route::middleware(['auth'])->group(function () {
    Route::get('/categories', \App\Livewire\Categories\Index::class)->name('categories.index');
    Route::get('/suppliers', \App\Livewire\Suppliers\Index::class)->name('suppliers.index');
    Route::get('/products', \App\Livewire\Products\Index::class)->name('products.index');
    Route::get('/customers', \App\Livewire\Customers\Index::class)->name('customers.index');
    Route::get('/sales/create', \App\Livewire\Sales\Create::class)->name('sales.create');
    Route::get('/sales/invoices', \App\Livewire\Sales\InvoiceList::class)->name('sales.invoices');
    Route::get('/sales/{sale}', \App\Livewire\Sales\InvoiceView::class)->name('sales.show');
    Route::get('/purchases/create', \App\Livewire\Purchases\Create::class)->name('purchases.create');
    Route::get('/purchases/invoices', \App\Livewire\Purchases\InvoiceList::class)->name('purchases.invoices');
    Route::get('/purchases/{purchase}', \App\Livewire\Purchases\InvoiceView::class)->name('purchases.show');
    Route::get('/reports/dashboard', \App\Livewire\Reports\Dashboard::class)->name('reports.dashboard');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    
    // Ruta para la información de la empresa
    Route::get('/company-info', \App\Livewire\CompanyInfo::class)->name('company.info');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__ . '/auth.php';
