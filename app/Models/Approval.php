<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    public function items()
    {
        return $this->hasMany(approval_items::class, 'approval_id', 'id');
    }
}
