<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPdfController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::latest()->get();

        $pdf = Pdf::loadView('pdf.transactions', [
            'transactions' => $transactions,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('transactions-report.pdf');
    }
}
