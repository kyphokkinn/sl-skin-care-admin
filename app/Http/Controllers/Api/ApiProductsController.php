<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiProductsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_product";        
				$this->permalink   = "products";    
				$this->method_type = "get";    
				$this->postdata = null;
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				CRUDBooster::copyPostdata($postdata, $this->postdata);
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
				if (!empty($this->postdata['title'])) {
					$query->whereRaw('title LIKE \'%'.$this->postdata['title'].'%\'');
					unset($this->postdata['title']);
				}
				$query->where($this->postdata);
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}