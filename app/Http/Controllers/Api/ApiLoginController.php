<?php namespace App\Http\Controllers\Api;

use CRUDBooster;
use DB;
use Hash;

class ApiLoginController extends \crocodicstudio\crudbooster\controllers\ApiController
{

    public function __construct()
    {
        $this->table = "cms_users";
        $this->permalink = "login";
        $this->method_type = "get";
    }

    public function hook_before(&$postdata)
    {
        //This method will be execute before run the main process

    }

    public function hook_query(&$query)
    {
        //This method is to customize the sql query

    }

    public function hook_after($postdata, &$result)
    {
        //This method will be execute after run the main process
        $item = DB::table($this->table)
            ->where("phone", $postdata['phone'])
            ->first();
        if (Hash::check($postdata['password'], $item->password)) {
            if ($item->image) {
                $item->image = URL::to('/') . '/' . $item->image;
            }
            $result['api_message'] = 'success';
            $result['api_status'] = 1;
            $result['data'] = $item;

        } else {
            $result['data'] = null;
            $result['api_status'] = 0;
            $result['api_message'] = 'Invalid credentials. Check your phone number and password.';
        }
    }

}
