<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItemsModel extends Model
{
    protected $table = 'tb_order_detail';
    
    public function order()
    {
        return $this->belongsTo('App\OrderModel', 'order_id');
    }
    
}
