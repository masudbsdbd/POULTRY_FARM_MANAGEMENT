<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    //
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function incomeList()
    {
        return $this->belongsTo('App\Models\IncomeList', 'income_list_id');
    }
    public function account()
    {
        return $this->hasOne(Account::class);
    }
}
