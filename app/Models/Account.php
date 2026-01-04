<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function bankTransaction()
    {
        return $this->hasOne(BankTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'entry_by', 'id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sell_id');
    }
}
