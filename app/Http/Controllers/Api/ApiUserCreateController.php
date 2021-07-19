<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUserCreateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "user_create";    
				$this->method_type = "post";    
				$this->postdata = array();
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$postdata['id_cms_privileges'] = 4; // privilage for customer = 4
				$this->postdata = $postdata;
				if (!empty($postdata['phone'])) {
					$user = DB::table($this->table)
						->where('phone', $postdata['phone'])
						->first();
					if ($user) {
						if (!empty($postdata['token'])) {
							DB::table('tb_device_token')
								->where('user_id', $user->id)
								->update(['token' => $postdata['token']]);
						}
						unset($postdata['token']);
						DB::table($this->table)
							->where('id', $user->id)
							->update($postdata);
						$item = CRUDBooster::first($this->table, $user->id);

						$this->output([
							'api_status' => 1,
							'api_message' => 'success',
							'data' => CRUDBooster::getUserItem($item)
						]);
				
					}
				}
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				$item = CRUDBooster::first($this->table, $result['id']);
				$result['data'] = CRUDBooster::getUserItem($item);
				if (!empty($this->postdata['token'])) {
					DB::table('tb_device_token')
						->insert([
							'user_id' => $item->id,
							'token' => $this->postdata['token'],
							'status' => 'Active'
						]);
				}
		    }

		}