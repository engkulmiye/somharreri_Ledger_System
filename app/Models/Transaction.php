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
        'remaining_amount',
        'status',
        'paid_at',
        'parent_debt_id',
        'notes',
    ];



    /* ================= Relationships ================= */

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function debt()
    {
        return $this->belongsTo(Transaction::class, 'parent_debt_id');
    }

    public function payments()
    {
        return $this->hasMany(Transaction::class, 'parent_debt_id');
    }

    /* ================= Accessors ================= */

    public function getPartnerDisplayNameAttribute()
    {
        return $this->partner?->name ?? $this->manual_partner_name;
    }


    /* ================= Scopes ================= */

    public function scopeOpenDebts($query)
    {
        return $query
            ->where('type', 'debt')
            ->where('status', 'open');
    }

    public static function getUniqueOpenDebts()
    {
        return self::openDebts()
            ->get()
            ->groupBy(function ($debt) {
                return $debt->partner_display_name;
            })
            ->map(function ($group) {
                // Return the latest open debt for same name
                return $group->sortByDesc('id')->first();
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

        static::saved(function ($tx) {

            // When payment saved → check debt
            if ($tx->parent_debt_id) {
                $debt = $tx->debt;

                if ($debt && $debt->remaining_amount <= 0) {
                    $debt->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }
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

            if ($transaction->type !== 'payment' || !$transaction->parent_debt_id) {
                return;
            }

            $debt = self::find($transaction->parent_debt_id);

            if (!$debt) {
                return;
            }

            // ✅ Subtract FULL payment including commission
            $debt->remaining_amount -= $transaction->total_amount;

            if ($debt->remaining_amount <= 0) {
                $debt->remaining_amount = 0;
                $debt->status = 'paid';
                $debt->paid_at = now();
            }

            $debt->save();
        });
    }
}
