<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $guarded = ['id'];
    protected $table = "products";

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
