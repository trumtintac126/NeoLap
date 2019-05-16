<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    public $timestamps = false;

    protected $fillable = [
        'order_date',
        'category_order',
        'price',
        'quantity',
        'total_detail'
    ];

    protected $hidden = [
    ];

}
