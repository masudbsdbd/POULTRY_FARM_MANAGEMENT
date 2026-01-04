<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'warehouse_manager', 'id');
    }


}
