<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    // public function expense()
    // {
    //     return $this->hasMany('App\Models\Expense');
    // }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }

    public function expenseHead()
    {
        return $this->belongsTo('App\Models\ExpenseHead', 'expense_head_id');
    }
    public function account()
    {
        return $this->hasOne(Account::class);
    }
}
