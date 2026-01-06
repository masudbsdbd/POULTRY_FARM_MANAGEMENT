<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultryOthersIncome extends Model
{
    protected $fillable = [
        'batch_id',
        'title',
        'amount',
        'note',
        'income_date'
    ];

    protected $casts = [
        'income_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(PoultryBatch::class);
    }
}
