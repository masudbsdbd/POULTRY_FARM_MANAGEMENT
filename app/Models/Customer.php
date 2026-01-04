<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'type');
    }
    
    public function customerReturn()
    {
        return $this->hasMany('App\Models\CustomerReturn');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

        
}
