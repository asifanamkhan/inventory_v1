<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Livewire\Dashboard\Admin\Branch\Branch;
use App\Livewire\Dashboard\Admin\Company;
use App\Livewire\Dashboard\Admin\User\UserCreate;
use App\Livewire\Dashboard\Admin\User\UserList;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\People\Customer\Customer;
use App\Livewire\Dashboard\People\Customer\CustomerCreate;
use App\Livewire\Dashboard\People\Customer\CustomerEdit;
use App\Livewire\Dashboard\People\Supplier\Supplier;
use App\Livewire\Dashboard\People\Supplier\SupplierCreate;
use App\Livewire\Dashboard\People\Supplier\SupplierEdit;
use App\Livewire\Dashboard\Product\Brand\Brand;
use App\Livewire\Dashboard\Product\Category\Category;
use App\Livewire\Dashboard\Product\Product\Product;
use App\Livewire\Dashboard\Product\Product\ProductCreate;
use App\Livewire\Dashboard\Product\Product\ProductEdit;
use App\Livewire\Dashboard\Product\Unit\ProductUnit;
use App\Livewire\Dashboard\Purchase\Purchase;
use App\Livewire\Dashboard\Purchase\PurchaseCreate;
use App\Livewire\Dashboard\Purchase\PurchaseDetails;
use App\Livewire\Dashboard\Purchase\PurchaseEdit;
use App\Livewire\Dashboard\Purchase\PurchaseReturn;
use App\Livewire\Dashboard\Purchase\PurchaseReturnCreate;
use App\Livewire\Dashboard\Purchase\PurchaseReturnEdit;
use App\Livewire\Dashboard\Sale\Sale;
use App\Livewire\Dashboard\Sale\SaleCreate;
use App\Livewire\Dashboard\Sale\SaleDetails;
use App\Livewire\Dashboard\Sale\SaleEdit;

Livewire::setUpdateRoute(function ($handle) {
    $path = env('LIVEWIRE_UPDATE_PATH').'/livewire/update';
    return Route::post($path, $handle);
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('company', Company::class)->name('company');
    Route::get('branch', Branch::class)->name('branch');
    Route::get('users', UserList::class)->name('user');
    Route::get('user-create', UserCreate::class)->name('user-create');

    Route::get('supplier', Supplier::class)->name('supplier');
    Route::get('supplier/create', SupplierCreate::class)->name('supplier-create');
    Route::get('supplier/{id}/edit', SupplierEdit::class)->name('supplier-edit');

    Route::get('customer', Customer::class)->name('customer');
    Route::get('customer/create', CustomerCreate::class)->name('customer-create');
    Route::get('customer/{id}/edit', CustomerEdit::class)->name('customer-edit');

    Route::get('brand', Brand::class)->name('brand');
    Route::get('category', Category::class)->name('category');
    Route::get('unit', ProductUnit::class)->name('unit');

    Route::get('product', Product::class)->name('product');
    Route::get('product/create', ProductCreate::class)->name('product-create');
    Route::get('product/{u_code}/edit', ProductEdit::class)->name('product-edit');

    Route::get('purchase', Purchase::class)->name('purchase');
    Route::get('purchase/create', PurchaseCreate::class)->name('purchase-create');
    Route::get('purchase/{purchase_id}/edit', PurchaseEdit::class)->name('purchase-edit');
    Route::get('purchase/{purchase_id}/details', PurchaseDetails::class)->name('purchase-details');
    Route::get('purchase-invoice/{purchase_id}', [PurchaseController::class, 'invoice'])->name('purchase-invoice');

    Route::get('purchase/return', PurchaseReturn::class)->name('purchase-return');
    Route::get('purchase/return/create', PurchaseReturnCreate::class)->name('purchase-return-create');
    Route::get('purchase/return/{purchase_id}/edit', PurchaseReturnEdit::class)->name('purchase-return-edit');
    // Route::get('purchase/return/{purchase_id}/details', PurchaseDetails::class)->name('purchase-return-details');
    Route::get('purchase/return-invoice/{purchase_id}', [PurchaseController::class, 'invoice'])->name('purchase-return-invoice');


    Route::get('sale', Sale::class)->name('sale');
    Route::get('sale/create', SaleCreate::class)->name('sale-create');
    Route::get('sale/{sale_id}/edit', SaleEdit::class)->name('sale-edit');
    Route::get('sale/{sale_id}/details', SaleDetails::class)->name('sale-details');
    Route::get('sale-invoice/{sale_id}', [SaleController::class, 'invoice'])->name('sale-invoice');

});