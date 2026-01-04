<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockItem()
    {
        return $this->hasOne(StockItem::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }
    
    public function sellRecord()
    {
        return $this->hasMany('App\Models\SellRecord');
    }


    public function purchaseItem()
    {
        return $this->hasOne('App\Models\PurchaseItem');
    }


    // public function purchaseBatch()
    // {
    //     return $this->belongsTo('App\Models\PurchaseBatch', 'sell_id');
    // }

    public function damage()
    {
        return $this->hasOne('App\Models\Damage');
    }
}
