<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoultryBatch extends Model
{
    protected $fillable = [
        'customer_id',
        'batch_name',
        'batch_number',
        'chicken_type',
        'total_chickens',
        'price_per_chicken',
        'chicken_grade',
        'hatchery_name',
        'shed_number',
        'target_feed_qty',
        'terget_feed_unit',
        'batch_start_date',
        'batch_close_date',
        'batch_description',
        'status',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
