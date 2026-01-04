<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    //
    public function assetHead(){
        return $this->belongsTo(AssetHead::class);
    }

}
