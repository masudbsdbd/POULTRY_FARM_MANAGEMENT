<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function damage()
    {
        return $this->hasOne('App\Models\Damage');
    }
}
