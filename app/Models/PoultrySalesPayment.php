<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultrySalesPayment extends Model
{
    protected $fillable = [
        'sale_id',
        'amount',
    ];
}
