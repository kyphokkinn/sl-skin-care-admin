<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'cms_users';

    
    public function tokens()
    {
        return $this->hasMany('App\TokenModel', 'user_id', 'id');
    }
    
}
