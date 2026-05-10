<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerTransactionPdfController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);





use App\Http\Controllers\ExportPdfController;

Route::get('/export/transactions/pdf', [ExportPdfController::class, 'transactions'])
    ->name('export.transactions.pdf');

Route::get(
    '/customers/{customer}/transactions/pdf',
    [CustomerTransactionPdfController::class, 'export']
)->name('customer.transactions.pdf');


use App\Http\Controllers\StatementPdfController;

Route::get('/statement/pdf', [StatementPdfController::class, 'download'])
    ->name('statement.pdf');
