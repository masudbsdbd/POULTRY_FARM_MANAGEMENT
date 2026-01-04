<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sellRecords()
    {
        return $this->hasMany(SellRecord::class);
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }
}
