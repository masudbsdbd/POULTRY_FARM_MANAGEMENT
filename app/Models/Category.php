<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
