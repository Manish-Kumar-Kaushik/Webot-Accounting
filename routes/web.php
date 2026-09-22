<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankingController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Installer Routes (Pre-installation & Setup)
|--------------------------------------------------------------------------
*/
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('index');
    Route::post('/verify-customer', [InstallController::class, 'verifyCustomer'])->name('verify');
    Route::post('/test-db', [InstallController::class, 'testDatabase'])->name('test_db');
    Route::post('/process', [InstallController::class, 'executeInstall'])->name('process');
    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});

/*
|--------------------------------------------------------------------------
| Public Uploads Fallback (Ensures logos & media serve under subdirectories)
|--------------------------------------------------------------------------
*/
Route::get('/uploads/{path}', function ($path) {
    $filePath = public_path('uploads/' . $path);
    if (!file_exists($filePath)) {
        $filePath = base_path('uploads/' . $path);
    }
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('uploads.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public Invoice View (Client Portal)
|--------------------------------------------------------------------------
*/
Route::get('/invoices/public/{token}', [InvoiceController::class, 'publicShow'])->name('invoices.public');

Route::get('/admin', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Accounting Application Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Root redirect to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Invoices & Sales
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::post('/invoices/{id}/payment', [InvoiceController::class, 'recordPayment'])->name('invoices.payment');
    Route::post('/invoices/{id}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
    Route::post('/invoices/{id}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.sendEmail');

    // Bills & Expenses
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
    Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
    Route::get('/bills/{id}', [BillController::class, 'show'])->name('bills.show');
    Route::get('/bills/{id}/edit', [BillController::class, 'edit'])->name('bills.edit');
    Route::put('/bills/{id}', [BillController::class, 'update'])->name('bills.update');
    Route::get('/bills/{id}/print', [BillController::class, 'print'])->name('bills.print');
    Route::delete('/bills/{id}', [BillController::class, 'destroy'])->name('bills.destroy');
    Route::post('/bills/{id}/payment', [BillController::class, 'recordPayment'])->name('bills.payment');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Vendors
    Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
    Route::get('/vendors/{id}', [VendorController::class, 'show'])->name('vendors.show');
    Route::get('/vendors/{id}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('/vendors/{id}', [VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    // Products & Services (Items)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Banking & Ledger
    Route::get('/banking', [BankingController::class, 'index'])->name('banking.index');
    Route::post('/banking', [BankingController::class, 'store'])->name('banking.store');
    Route::put('/banking/accounts/{id}', [BankingController::class, 'updateAccount'])->name('banking.accounts.update');
    Route::delete('/banking/accounts/{id}', [BankingController::class, 'destroyAccount'])->name('banking.accounts.destroy');
    Route::get('/banking/transfer', [BankingController::class, 'transferForm'])->name('banking.transfer');
    Route::post('/banking/transfer', [BankingController::class, 'transfer'])->name('banking.transfer.post');
    Route::get('/banking/transactions', [BankingController::class, 'transactions'])->name('banking.transactions');
    Route::delete('/banking/transactions/{id}', [BankingController::class, 'destroyTransaction'])->name('banking.transactions.destroy');

    // Reports
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    Route::get('/reports/income-expense', [ReportController::class, 'incomeExpense'])->name('reports.income_expense');
    Route::get('/reports/tax-summary', [ReportController::class, 'taxSummary'])->name('reports.tax_summary');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/company', [SettingController::class, 'updateCompany'])->name('settings.company');
    Route::post('/settings/categories', [SettingController::class, 'storeCategory'])->name('settings.categories');
    Route::post('/settings/taxes', [SettingController::class, 'storeTax'])->name('settings.taxes');
    Route::post('/settings/ai', [SettingController::class, 'updateAi'])->name('settings.ai');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');

    // System Updates
    Route::get('/updates', [UpdateController::class, 'index'])->name('updates.index');
    Route::get('/updates/check', [UpdateController::class, 'check'])->name('updates.check');
    Route::post('/updates/apply', [UpdateController::class, 'apply'])->name('updates.apply');

    // AI Assistant
    Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');
});
