<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultryExpense extends Model
{
    protected $fillable = [
        'batch_id',
        'expense_title',
        'invoice_number',
        'category',
        'feed_name',
        'feed_type',
        'medicine_name',
        'transaction_type',
        'quantity',
        'unit',
        'price',
        'total_amount',
        'description',
        'expense_date'
    ];


    protected $casts = [
        'expense_date' => 'date:Y-m-d',
    ];

    public function payments()
    {
        return $this->hasMany(PoultryExpensePayment::class, 'expense_id');
    }

    public function batch()
    {
        return $this->belongsTo(PoultryBatch::class, 'batch_id');
    }
}
