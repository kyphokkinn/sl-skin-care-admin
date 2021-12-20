<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
use Doctrine\Common\Collections\Collection;
use Illuminate\Support\Arr;

use function PHPSTORM_META\map;

class ApiWishlistsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_wishlist";        
				$this->permalink   = "wishlists";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				if (!empty($postdata['created_by']) && count($result['data']) > 0) {
			collect($result['data'])->map(function($item){
					$item->product=DB::table('tb_product')
					->where("id",$item->product_id)
					->whereNull('deleted_at')
					->first();
					return $item;
				});
			

				}


		    }

		}