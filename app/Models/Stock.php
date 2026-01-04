<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(PurchaseBatch::class, 'purchase_batch_id', 'id');
    }

    public function purchaseBatch()
    {
        return $this->belongsTo('App\Models\PurchaseBatch', 'purchase_batch_id');
    }
}
