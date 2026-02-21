<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


use App\Http\Controllers\ExportPdfController;

Route::get('/export/transactions/pdf', [ExportPdfController::class, 'transactions'])
    ->name('export.transactions.pdf');
