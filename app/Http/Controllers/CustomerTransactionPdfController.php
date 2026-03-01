<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerTransactionPdfController extends Controller
{
    public function export(Customer $customer, Request $request)
    {
        $transactions = $customer->customertransactions()
            ->latest()
            ->orderBy("date", "asc")
            ->get(); // later we can add filters if needed

        $pdf = Pdf::loadView('pdf.customertransaction', [
            'customer' => $customer,
            'transactions' => $transactions,
        ]);

        return $pdf->download(
            'customer-transactions-' . str($customer->name)->slug() . '.pdf'
        );
    }
}
