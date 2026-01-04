<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
