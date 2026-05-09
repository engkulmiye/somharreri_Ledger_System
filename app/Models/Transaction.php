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

        public function getPreviousTotalAttribute()
    {
        return self::where('partner_id', $this->partner_id)
            ->where('id', '<', $this->id)
            ->sum('total_amount');
    }

    public function getAccumulatedTotalAttribute()
    {
        return $this->previous_total + $this->total_amount;
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
