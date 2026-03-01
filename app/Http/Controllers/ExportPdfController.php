<?php

namespace App\Http\Controllers;


use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPdfController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::orderBy('date', 'asc')->get();

        $pdf = Pdf::loadView('pdf.transactions', [
            'transactions' => $transactions,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('transactions-report.pdf');
    }
}
