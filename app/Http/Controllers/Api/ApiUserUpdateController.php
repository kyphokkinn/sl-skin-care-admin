<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUserUpdateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "user_update";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				if (!empty($postdata['token'])) {
					DB::table('tb_device_token')
						->where('user_id', $postdata['id'])
						->update(['token'=>$postdata['token']]);
				}
				unset($postdata['token']);
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				$item = CRUDBooster::first($this->table, $postdata['id']);
				$result['data'] = CRUDBooster::getUserItem($item);
				$result['data'] = $item;
		    }

		}