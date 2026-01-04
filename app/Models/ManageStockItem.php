<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageStockItem extends Model
{
    //
        public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    
    public function batch()
    {
        return $this->belongsTo('App\Models\PurchaseBatch', 'batch_id');
    }

}
