<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function TrasactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
