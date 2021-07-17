<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'tb_order';
    
    public function orderItems()
    {
        return $this->hasMany('App\OrderItemsModel', 'order_id', 'id');
    }
    
}
