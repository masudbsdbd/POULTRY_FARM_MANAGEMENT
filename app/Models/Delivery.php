<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public function sell(){
        return $this->belongsTo(Sell::class);
    }
}
