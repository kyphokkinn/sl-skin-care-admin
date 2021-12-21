<?php namespace App\Http\Controllers\Api;

use CRUDBooster;
use DB;
use function PHPSTORM_META\map;

class ApiWishlistsController extends \crocodicstudio\crudbooster\controllers\ApiController
{

    function __construct()
    {
        $this->table = "tb_wishlist";
        $this->permalink = "wishlists";
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
        if (!empty($postdata['created_by']) && count($result['data']) > 0) {
            collect($result['data'])->map(function ($item) {
                if ($item->product_id != null) {
                    $item->product = DB::table('tb_product')
                        ->where("id", $item->product_id)
                        ->whereNull('deleted_at')
                        ->first();
                    if ($item->product->image_path) {
                        $item->product->image_path = url($item->product->image_path);
                    }
                }
                if ($item->promotion_id != null) {
                    $item->promotion = DB::table('tb_promotion')
                        ->where("id", $item->promotion_id)
                        ->whereNull('deleted_at')
                        ->first();
                    if ($item->promotion->image_path) {
                        $item->promotion->image_path = url($item->promotion->image_path);
                    }
                }

                return $item;
            });

        }

    }

}
