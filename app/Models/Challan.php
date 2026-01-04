<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    //
    public function floor()
    {
        return $this->belongsTo(FloorInfo::class, 'floor_id');
    }
}
