<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'entry_by');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function damage()
    {
        return $this->hasMany(Damage::class);
    }

    public function batch()
    {
        return $this->hasOne(PurchaseBatch::class);
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
