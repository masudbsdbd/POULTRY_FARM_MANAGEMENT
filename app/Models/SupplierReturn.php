<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierReturn extends Model
{
    //
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function purchaseBatch()
    {
        return $this->belongsTo(PurchaseBatch::class);
    }

    public function account()
    {
        // return $this->hasOne(Account::class);
        return $this->hasOne(Account::class, 'purchase_return_id');
    }
}
