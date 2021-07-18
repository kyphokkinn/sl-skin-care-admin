<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenModel extends Model
{
    protected $table = 'tb_device_token';
    
    public function user()
    {
        return $this->belongsTo('App\UserModel', 'user_id', 'id');
    }
    
}
