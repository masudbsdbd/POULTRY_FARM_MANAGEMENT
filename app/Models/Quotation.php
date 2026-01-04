<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'title',
        'customer_id',
        'quotation_date',
        'expiry_date',
        'status',
        'notes',
        'total_amount',
        'diagram_image'
    ];

    // QuotationItem relationship
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
