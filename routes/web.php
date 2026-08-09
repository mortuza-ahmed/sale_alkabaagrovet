<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalaryMonthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Models\Sale;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class,'dashboard'])->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{id}/payment', [CustomerController::class,'customer_payment'])->name('customers.payment');
    Route::post('customers/payment/{id}/delete', [CustomerController::class,'release_payment_delete'])->name('customers.releasePaymentDelete');
    Route::post('customers/payment/release', [CustomerController::class,'release_payment'])->name('customers.releasePayment');
    Route::post('customers/payment/release/update', [CustomerController::class,'release_payment_update'])->name('customers.releasePaymentUpdate');
    Route::match(['get','post'],'customers-payment-reports', [CustomerController::class,'payment_reports'])->name('customers.paymentReports');
    Route::get('customers-payment-reports-print', [CustomerController::class,'payment_reports_print'])->name('customers.paymentReportsPrint');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('units', UnitController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::get('purchases-reports', [PurchaseController::class,'reports'])->name('purchases.reports');
    Route::get('purchases/{id}/print',[PurchaseController::class,'print'])->name('purchases.print');
    Route::resource('sales', SaleController::class);
    Route::get('sales-reports', [SaleController::class,'reports'])->name('sales.reports');
    Route::get('sales/{id}/print',[SaleController::class,'print'])->name('sales.print');
    Route::get('stocks', [StockController::class,'index'])->name('stocks.index');
    Route::get('stocks/log', [StockController::class,'log'])->name('stocks.log');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('salaries', SalaryMonthController::class);
    Route::post('salaries/payment/release', [SalaryMonthController::class,'release_payment'])->name('salaries.releasePayment');
    Route::match(['get','post'],'profile',[DashboardController::class,'profile'])->name('profile');
    // ajax
    Route::get('/customers/{customer}/sales', [CustomerController::class, 'getSales'])->name('customers.getSales');
});
// Route::get('/check',function(){
//     $sale = Sale::get();
//     foreach($sale as $s){
//         $sale = Sale::find($s->id);
//         $sale->grand_total = $sale->total;
//         $sale->save();
//     }
// });
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
    return 'cleared';
})->name('clear');
require __DIR__.'/auth.php';
