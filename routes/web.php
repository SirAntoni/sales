<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CanceledController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DocumentController;

Route::middleware(['guest'])->group(function () {
    Route::get("/login",[AuthController::class,'showLoginForm'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class,'index'])->middleware('can:dashboard')->name('dashboard');
    Route::resource('categories', CategoryController::class)->middleware('can:store');
    Route::resource('brands', BrandController::class)->middleware('can:store');
    Route::resource('articles', ArticleController::class)->middleware('can:store');
    Route::resource('providers', ProviderController::class)->middleware('can:purchases');
    Route::resource('clients', ClientController::class)->middleware('can:sales');
    Route::resource('users', UserController::class)->middleware('can:users');
    Route::resource('vouchers', VoucherController::class)->middleware('can:settings');
    Route::get('settings', [SettingController::class,'index'])->name('settings')->middleware('can:settings');
    Route::resource('contacts', ContactController::class)->middleware('can:settings');
    Route::resource('payment-methods', PaymentMethodController::class)->middleware('can:settings');
    Route::resource('purchases', PurchaseController::class)->middleware('can:purchases');
    Route::get('canceled_purchases', [CanceledController::class,'purchases'])->name('canceled_purchases')->middleware('can:purchases');
    Route::resource('sales', SaleController::class)->middleware('can:sales');
    Route::resource('documents', DocumentController::class)->middleware('can:kardex');
    Route::get('canceled', [CanceledController::class,'index'])->name('canceled')->middleware('can:sales');
    Route::get('reports', [ReportController::class,'index'])->name('reports')->middleware('can:reports');
    Route::get('reports/dayli/export', [ReportController::class, 'dayli'])->name('reports.dayli.export')->middleware('can:reports');
    Route::get('reports/custom/export', [ReportController::class, 'custom'])->name('reports.custom.export')->middleware('can:reports');
    Route::get('reports/month/export', [ReportController::class, 'month'])->name('reports.month.export')->middleware('can:reports');
    Route::get('kardex', [KardexController::class,'index'])->name('kardex')->middleware('can:kardex');
    Route::get('pdf/{id}', [SaleController::class,'pdf'])->name('pdf.view');
    Route::post("/logout",[AuthController::class,'logout'])->name('logout');

});


Route::get('theme-switcher', function () {
    return "Hola Mundo";
})->name('theme-switcher');


