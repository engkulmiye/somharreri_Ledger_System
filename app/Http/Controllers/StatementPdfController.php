<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StatementPdfController extends Controller
{
    public function download(Request $request)
    {
        // Get selected transaction
        $currentTransaction = Transaction::findOrFail(
            $request->transaction_id
        );

        /*
        |--------------------------------------------------------------------------
        | Load ALL transactions BEFORE this transaction
        | + include current transaction itself
        |--------------------------------------------------------------------------
        */

        $transactions = Transaction::query()

            ->where(function ($query) use ($currentTransaction) {

                $query->whereDate('date', '<', $currentTransaction->date)

                    ->orWhere(function ($q) use ($currentTransaction) {

                        $q->whereDate('date', $currentTransaction->date)

                            ->where('id', '<=', $currentTransaction->id);
                    });
            })

            ->orderBy('date')

            ->orderBy('id')

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Calculate Running Balance
        |--------------------------------------------------------------------------
        */

        $runningBalance = 0;

        foreach ($transactions as $tx) {

            // Previous balance before current transaction
            $tx->previous_balance = $runningBalance;

            // Debt adds money
            if ($tx->type === 'debt') {

                $runningBalance += $tx->total_amount;
            }

            // Payment/company_paid subtracts money
            elseif (
                $tx->type === 'payment' ||
                $tx->type === 'company_paid'
            ) {

                $runningBalance -= $tx->total_amount;
            }

            // Save running balance for PDF display
            $tx->running_balance = $runningBalance;
        }

        /*
        |--------------------------------------------------------------------------
        | Final Closing Balance
        |--------------------------------------------------------------------------
        */

        $closingBalance = $runningBalance;

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView('pdf.statement', [

            'transactions' => $transactions,

            'currentTransaction' => $currentTransaction,

            'closingBalance' => $closingBalance,

        ]);

        return $pdf->download(
            'statement-' . $currentTransaction->id . '.pdf'
        );
    }
}
