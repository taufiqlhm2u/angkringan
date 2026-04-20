<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\number;

class Product extends Model
{
    protected $guarded = [];

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function TransactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getFormatPriceAttribute()
    {
        return number_format($this->price, 0, '.', '.');
    }
}
