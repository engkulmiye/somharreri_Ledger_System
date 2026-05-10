<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



use App\Models\Partner;


class Transaction extends Model
{

    protected $fillable = [
        'date',
        'partner_id',
        'manual_partner_name',
        'type',
        'cash_ksh',
        'rate',
        'amount_usd',
        'commission_rate',
        'commission_amount',
        'total_amount',
        'running_balance',
        'notes',
    ];



    /* ================= Relationships ================= */

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }


    /* ================= Accessors ================= */

    public function getPartnerDisplayNameAttribute()
    {
        return $this->partner?->name ?? $this->manual_partner_name;
    }

    public function getPreviousBalanceAttribute()
    {
        $transactions = self::where(function ($query) {
            $query->whereDate('date', '<', $this->date)
                ->orWhere(function ($q) {
                    $q->whereDate('date', $this->date)
                        ->where('id', '<', $this->id);
                });
        })
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $balance = 0;

        foreach ($transactions as $tx) {

            if ($tx->type === 'debt') {
                $balance += $tx->total_amount;
            }

            if ($tx->type === 'payment' || $tx->type === 'company_paid') {
                $balance -= $tx->total_amount;
            }
        }

        return $balance;
    }

    public function getRunningLedgerBalanceAttribute()
    {
        $balance = $this->previous_balance;

        if ($this->type === 'debt') {
            $balance += $this->total_amount;
        }

        if ($this->type === 'payment' || $this->type === 'company_paid') {
            $balance -= $this->total_amount;
        }

        return $balance;
    }

    public function getTypeEffectAttribute()
    {
        return in_array($this->type, ['payment', 'company_paid'])
            ? '-'
            : '+';
    }



    /* ================= Auto Logic ================= */

    protected static function booted()
    {

        static::saving(function ($tx) {

            // Calculate commission
            $tx->commission_amount =
                ($tx->amount_usd * $tx->commission_rate) / 100;

            $tx->total_amount =
                $tx->amount_usd + $tx->commission_amount;

            // Get last balance for this partner
            $lastBalance = self::where('partner_id', $tx->partner_id)
                ->latest('id')
                ->value('running_balance') ?? 0;

            // If company paid → reduce balance
            if ($tx->type === 'company_paid') {
                $tx->running_balance = $lastBalance - $tx->total_amount;
            }

            // If company received or debt → increase balance
            else {
                $tx->running_balance = $lastBalance + $tx->total_amount;
            }
        });
    }
}
