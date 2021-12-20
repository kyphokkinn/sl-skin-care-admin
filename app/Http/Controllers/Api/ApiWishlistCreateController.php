<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiWishlistCreateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_wishlist";        
				$this->permalink   = "wishlist_create";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}