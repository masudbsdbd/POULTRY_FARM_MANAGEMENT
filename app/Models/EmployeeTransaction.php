<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTransaction extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }
}
