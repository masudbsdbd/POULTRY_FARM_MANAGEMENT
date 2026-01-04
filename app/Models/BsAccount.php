<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BsAccount extends Model
{
    //
    
    public function bsType()
    {
        return $this->belongsTo('App\Models\BsType', 'account_sub_type');
    }
}
