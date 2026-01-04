<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    //
    public function receivableHead(){
        return $this->belongsTo(ReceivableHead::class);
    }

    public function sell(){
        return $this->belongsTo(Sell::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
