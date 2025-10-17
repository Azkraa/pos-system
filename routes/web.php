<?php

use App\Models\Sale;
use App\Livewire\POS;
use Livewire\Volt\Volt;
use Laravel\Fortify\Features;
use App\Livewire\Items\EditItem;
use App\Livewire\Items\ListItems;
use App\Livewire\Sales\ListSales;
use App\Livewire\Items\CreateItem;
use App\Livewire\Items\EditInventory;
use App\Livewire\Management\EditUser;
use Illuminate\Support\Facades\Route;
use App\Livewire\Management\ListUsers;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Items\CreateInventory;
use App\Livewire\Items\ListInventories;
use App\Livewire\Management\CreateUser;
use App\Livewire\Customer\ListCustomers;
use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Management\EditPaymentMethod;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\Management\CreatePaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

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

Route::get('/sales/{sale}/receipt', function (\App\Models\Sale $sale) {
    $sale->load(['saleItems.item', 'customer', 'paymentMethod']);
    return view('pdf', ['records' => collect([$sale])]);
})->name('sales.receipt');

Route::get('/sales/{sale}/receipt.view', function (\App\Models\Sale $sale) {
    $sale->load(['saleItems.item', 'customer', 'paymentMethod']);
    return view('receipt', ['records' => collect([$sale])]);
})->name('receipt.view');

Route::middleware(['auth'])->group(function () {
    // users
    Route::get('/manage-users',ListUsers::class)->name('users.index');
    Route::get('/edit-users/{record}',EditUser::class)->name('users.update');
    Route::get('/create-users',CreateUser::class)->name('users.create');
    // items
    Route::get('/manage-items',ListItems::class)->name('items.index');
    Route::get('/edit-item/{record}',EditItem::class)->name('item.update');
    Route::get('/create-item',CreateItem::class)->name('items.create');
    // inventories
    Route::get('/manage-inventories',ListInventories::class)->name('inventories.index');
    Route::get('/edit-inventory/{record}',EditInventory::class)->name('inventory.update');
    Route::get('/create-iinventory',CreateInventory::class)->name('inventory.create');
    // sales
    Route::get('/manage-sales',ListSales::class)->name('sales.index');
    // customers
    Route::get('/manage-customers',ListCustomers::class)->name('customers.index');
    Route::get('/edit-customers/{record}',EditCustomer::class)->name('customers.update');
    Route::get('/create-customers',CreateCustomer::class)->name('customers.create');
    // payment method
    Route::get('/manage-payment-methods',ListPaymentMethods::class)->name('payment.method.index');
    Route::get('/edit-payment-method/{record}',EditPaymentMethod::class)->name('payment.method.update');
    Route::get('/create-payment-method',CreatePaymentMethod::class)->name('payment.method.create');


    Route::get('/pos', POS::class)->name('pos');
});

require __DIR__.'/auth.php';
