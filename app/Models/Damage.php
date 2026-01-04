<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function purchaseBatch()
    {
        return $this->belongsTo('App\Models\PurchaseBatch', 'purchase_batch_id');
    }


    // public function purchaseItem()
    // {
    //     return $this->belongsTo(PurchaseItem::class, 'purchase_id', 'purchase_id');
    // }

    public function account()
    {
        // return $this->hasOne(Account::class);
        return $this->hasOne(Account::class, 'damage_id');
    }
}
