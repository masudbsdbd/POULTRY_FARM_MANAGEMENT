<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    //
    public function floor()
    {
        return $this->hasMany(FloorInfo::class, 'building_id');
    }
}
