<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = [];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
