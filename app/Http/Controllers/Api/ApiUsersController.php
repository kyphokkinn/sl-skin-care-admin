<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUsersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "users";    
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
				if ($result['data'] != null) {
					$data = collect($result['data'])->map(function($item) {
						$item->chat_id = 'C'.$item->id;
						if ($item->id_cms_privileges != 4) {
							$item->chat_id = 'SL168';
						}
						return $item;
					})->all();
					$result['data'] = $data;
				}
		    }

		}