<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Transaction;


class Partner extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }




    public function getTotalDebtAttribute()
    {
        return $this->transactions()
            ->where('type', 'debt')
            ->sum('total_amount');
    }

    public function getTotalPaidAttribute()
    {
        return $this->transactions()
            ->whereIn('type', ['payment', 'company_paid'])
            ->sum('total_amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_debt - $this->total_paid;
    }
}
