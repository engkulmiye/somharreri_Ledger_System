<?php


use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});





use App\Http\Controllers\ExportPdfController;

Route::get('/export/transactions/pdf', [ExportPdfController::class, 'transactions'])
    ->name('export.transactions.pdf');
