<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "invoices";
    protected $fillable = [
        'invoice_number',
        'quotation_id',
        'invoice_date',
        'due_date',
        'percentage',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'notes',
    ];


    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItems::class);
    }


    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }
}
