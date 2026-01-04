<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function floor()
    {
        return $this->belongsTo(FloorInfo::class, 'floor_id');
    }
}
