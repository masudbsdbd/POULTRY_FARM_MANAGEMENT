<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultryExpensePayment extends Model
{
    protected $fillable = [
        'expense_id',
        'payment_date',
        'amount',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function expense()
    {
        return $this->belongsTo(PoultryExpense::class, 'expense_id');
    }
}
