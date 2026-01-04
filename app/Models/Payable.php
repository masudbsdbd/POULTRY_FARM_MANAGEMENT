<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    public function payablesHead(){
        return $this->belongsTo(PayableHead::class);
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
