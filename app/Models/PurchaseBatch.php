<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseBatch extends Model
{
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function sellRecord()
    {
        return $this->hasOne('App\Models\SellRecord');
    }


    public function stock()
    {
        return $this->hasOne('App\Models\Stock');
    }


    public function damage()
    {
        return $this->hasMany('App\Models\Damage');
    }
}
