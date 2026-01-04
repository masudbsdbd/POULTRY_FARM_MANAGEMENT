<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class approval_items extends Model
{
    protected $table = 'approval_items';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function floor()
    {
        return $this->belongsTo(FloorInfo::class, 'floor_id', 'id');
    }

    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
}
