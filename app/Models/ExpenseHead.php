<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseHead extends Model
{
    //
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }
}
