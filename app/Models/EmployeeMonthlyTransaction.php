<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMonthlyTransaction extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function employeeTransactions(){
        return $this->hasMany(EmployeeTransaction::class, 'monthly_transaction_id');
    }

    public function empTrNotDeleted(){
        return $this->hasMany(EmployeeTransaction::class, 'monthly_transaction_id')->where('is_deleted', 0);
    }
}
