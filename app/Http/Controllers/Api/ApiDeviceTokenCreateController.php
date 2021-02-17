<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDeviceTokenCreateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_device_token";        
				$this->permalink   = "device_token_create";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$item = DB::table($this->table)
					->where($postdata)
					->first();
				if ($item) {
					$this->output([
						'api_status' => 1,
						'api_message' => 'success'
					]);
				} else {
					$item = DB::table($this->table)
						->where('token', $postdata['token'])
						->first();
					if ($item) {
						DB::table($this->table)
							->where('id', $item->id)
							->update(['user_id'=>$postdata['user_id']]);
						$this->output([
							'api_status' => 1,
							'api_message' => 'success'
						]);
					}
				}
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}