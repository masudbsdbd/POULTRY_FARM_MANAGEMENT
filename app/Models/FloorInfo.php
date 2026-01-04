<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FloorInfo extends Model
{
    //
    public function floor()
    {
        return $this->belongsTo(FloorInfo::class, 'floor_id');
    }


    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
     public function challanItems()
    {
        return $this->hasMany(ChallanItem::class, 'floor_id');
    }
}
