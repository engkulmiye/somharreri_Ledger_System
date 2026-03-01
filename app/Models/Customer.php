<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'location', 'total_debt', 'total_paid', 'balance'];

    public function customertransactions()
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    public function getTotalDebtAttribute()
    {
        return $this->customertransactions()
            ->where('type', 'debt')
            ->sum('total_amount');
    }

    public function getTotalPaidAttribute()
    {
        return $this->customertransactions()
            ->where('type', 'payment')
            ->sum('total_amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total_debt - $this->total_paid;
    }
}
