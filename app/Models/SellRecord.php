<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellRecord extends Model
{
    public function purchaseBatch()
    {
        return $this->belongsTo('App\Models\PurchaseBatch', 'purchase_batch_id');
    }

    public function sell()
    {
        return $this->belongsTo('App\Models\Sell', 'sell_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
