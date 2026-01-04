<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageStock extends Model
{
    //
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(ManageStockItem::class);
    }
}
