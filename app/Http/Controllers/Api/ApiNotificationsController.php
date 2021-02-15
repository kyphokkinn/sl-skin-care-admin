<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiNotificationsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_notification";        
				$this->permalink   = "notifications";    
				$this->method_type = "get";    
				$this->postdata = array();
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				CRUDBooster::copyPostdata($postdata, $this->postdata);
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
				if (!empty($this->postdata['user_id'])) {
					$query->whereRaw('FIND_IN_SET('.$this->postdata['user_id'].',user_id_list) OR user_id=\''.$this->postdata['user_id'].'\'');
					unset($this->postdata['user_id']);
				}
				$query->where($this->postdata);
				$query->orWhere('is_all', 'Yes');
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				if ($result['data'] != null) {
					$result['data'] = $result['data']->map(function($item,$key){
						$item->content_text = trim(strip_tags($item->content));
						return $item;
					});
				}
		    }

		}