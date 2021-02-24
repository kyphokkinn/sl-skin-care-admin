<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use App\Http\Controllers\AdminOrdersController;

		class ApiOrderUpdateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_order";        
				$this->permalink   = "order_update";    
				$this->method_type = "post";    
				$this->is_change_status = false;
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$item = CRUDBooster::first($this->table, $postdata['id']);
				if ($item->status_delivery != $postdata['status_delivery']) {
					$this->is_change_status = true;
				}
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				if ($this->is_change_status) {
					AdminOrdersController::update_status($postdata['id']);
				}
				AdminOrdersController::sendMailOrder($postdata['id'], 'update_order');
		    }

		}