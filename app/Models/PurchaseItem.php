<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    // public function product()
    // {
    //     return $this->belongsTo('App\Models\Product', 'sell_id');
    // }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }


    // public function damage()
    // {
    //     return $this->hasOne(Damage::class, 'purchase_id', 'purchase_id');
    // }

}
