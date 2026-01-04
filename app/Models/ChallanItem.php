<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallanItem extends Model
{
    protected $table = 'challan_items';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function floor()
    {
        return $this->belongsTo(FloorInfo::class, 'floor_id');
    }

    public function challan()
    {
        return $this->belongsTo(Challan::class, 'challan_id');
    }
}
