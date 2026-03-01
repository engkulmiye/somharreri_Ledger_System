<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerTransaction extends Model
{
    protected $fillable = [
        'date',
        'customer_id',
        'manual_partner_name',
        'type',
        'cash_ksh',
        'rate',
        'amount_usd',
        'commission_rate',
        'commission_amount',
        'total_amount',
        'remaining_amount',
        'status',
        'parent_debt_id',
        'paid_at',
        'notes',
    ];


    /* ================= Relationships ================= */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function allocatedDebts()
    {
        return $this->belongsToMany(
            Transaction::class,
            'debt_payment_allocations',
            'payment_id',
            'debt_id'
        )->withPivot('amount_allocated');
    }

    public function paymentAllocations()
    {
        return $this->hasMany(
            DebtPaymentAllocation::class,
            'payment_id'
        );
    }
    /* ================= Accessors ================= */

    public function getPartnerDisplayNameAttribute()
    {
        return $this->customer?->name ?? $this->manual_partner_name;
    }



    /* ================= Combined Debt Loader ================= */

    public static function getGroupedOpenDebts()
    {
        return self::where('type', 'debt')
            ->where('status', 'open')
            ->get()
            ->groupBy(function ($debt) {
                return $debt->partner_display_name;
            })
            ->map(function ($debts, $name) {

                return [
                    'name' => $name,
                    'total_balance' => $debts->sum('remaining_amount'),
                    'debt_ids' => $debts->pluck('id')->toArray(),
                ];
            });
    }



    /* ================= Auto Logic ================= */

    protected static function booted()
    {

        static::saving(function ($tx) {

            // Commission only on debt
            if ($tx->type === 'debt') {
                $tx->commission_amount =
                    ($tx->amount_usd * $tx->commission_rate) / 100;

                $tx->total_amount =
                    $tx->amount_usd + $tx->commission_amount;
            }

            // Payments reduce balance
            if ($tx->type === 'payment') {
                $tx->commission_amount = ($tx->amount_usd * $tx->commission_rate) / 100;
                $tx->total_amount =
                    $tx->amount_usd + $tx->commission_amount;
            }
        });

        // When a DEBT is created
        static::creating(function ($transaction) {
            if ($transaction->type === 'debt') {
                $transaction->remaining_amount = $transaction->total_amount;
                $transaction->status = 'open';
            }
        });

        // When a PAYMENT is created
        static::created(function ($transaction) {

            if ($transaction->type !== 'payment') {
                return;
            }

            $remainingPayment = $transaction->total_amount;

            $openDebts = self::where('type', 'debt')
                ->where('status', 'open')
                ->where('customer_id', $transaction->customer_id)
                ->orderBy('created_at') // FIFO
                ->get();

            foreach ($openDebts as $debt) {

                if ($remainingPayment <= 0) {
                    break;
                }

                $allocation = min($debt->remaining_amount, $remainingPayment);

                // Reduce debt
                $debt->remaining_amount -= $allocation;

                if ($debt->remaining_amount <= 0) {
                    $debt->remaining_amount = 0;
                    $debt->status = 'paid';
                    $debt->paid_at = now();
                }

                $debt->save();

                // Save allocation record
                DB::table('debt_payment_allocations')->insert([
                    'payment_id' => $transaction->id,
                    'debt_id' => $debt->id,
                    'amount_allocated' => $allocation,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $remainingPayment -= $allocation;
            }
        });
    }
}
