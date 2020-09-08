<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];
    protected $with = ['basket'];

    public function basket()
    {
        return $this->hasMany(Basket::class);
    }
}
