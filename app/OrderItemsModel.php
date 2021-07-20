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

    public function product() 
    {
        return $this->hasOne('App\ProductModel', 'id', 'product_id');
    }

    public function promotion() 
    {
        return $this->hasOne('App\PromotionModel', 'id', 'promotion_id');
    }
    
}
