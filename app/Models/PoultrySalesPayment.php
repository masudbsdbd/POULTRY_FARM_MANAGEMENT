<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultrySalesPayment extends Model
{
    protected $fillable = ['sale_id', 'amount', 'payment_date', 'note'];

    public function sale()
    {
        return $this->belongsTo(PoultrySale::class, 'sale_id');
    }

    protected $casts = [
        'payment_date' => 'date:d M Y',
        'amount' => 'decimal:2',
    ];
}
