<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultrySale extends Model
{
    protected $fillable = [
        'batch_id',
        'sale_type',
        'quantity',
        'weight_kg',
        'rate',
        'total_amount',
        'paid_amount',
        'payment_status',
        'payment_date',
        'sale_date',
        'sales_channel',
        'note'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'payment_date' => 'date',
    ];


    public function payments()
    {
        return $this->hasMany(PoultrySalesPayment::class, 'sale_id');
    }
}
