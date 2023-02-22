<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $fillable=['product_id', 'order_id', 'price', 'qty'];
}
