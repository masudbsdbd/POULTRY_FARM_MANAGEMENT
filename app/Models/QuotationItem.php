<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $table = "quotation_items";

    protected $fillable = [
        'quotation_id',
        'product_id',
        'description',
        'qty',
        'unit_price',
        'total',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function approvals()
    {
        return $this->hasMany(approval_items::class, 'product_id', 'product_id');
    }
}
