<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiOrdersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_order";        
				$this->permalink   = "orders";    
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
				if (!empty($postdata['id'])) {
					$item = $result['data'][0];
					$item->order_items = DB::table('tb_order_detail')
						->select('tb_order_detail.*', 'tb_product.title')
						->join('tb_product', 'tb_product.id', 'tb_order_detail.product_id')
						->whereNull('tb_order_detail.deleted_at')
						->where('tb_order_detail.order_id', $item->id)
						->get();
					$result['data'] = $item;
				}
		    }

		}