<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use App\Http\Controllers\AdminOrdersController;

		class ApiOrderCreateController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_order";        
				$this->permalink   = "order_create";    
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
			
			public function create_order_with_items(\Illuminate\Http\Request $request)
			{
				$params = Request::all();
				$data = array();
				$fields = ['receiver_phone', 'total_amount', 'grand_total', 'order_items', 'order_date'];
				$data['api_status'] = 0;
				$msg = CRUDBooster::getValidateFields($fields, $params);
				if (count($msg)>0) {
					$data['api_message'] = implode(',', $msg);
					goto finish;
				}
				$fields = ['price', 'qty', 'amount', 'total_amount'];
				foreach ($params['order_items'] as $key => $value) {
					$msg = CRUDBooster::getValidateFields($fields, $value);
					if (count($msg)>0) {
						$data['api_message'] = implode(',', $msg);
						goto finish;
					}
				}
				DB::beginTransaction();
				try {
					$user = null;
					if (empty($params['customer_id'])) {
						if ($cus = self::check_user_exist($params['receiver_phone'])) {
							 $params['customer_id'] = $cus->id;
							 $params['created_by'] = $cus->id;
							 $user = $cus;
						} else {
							$user = self::create_user($params);
							$params['created_by'] = $user->id;
							$params['customer_id'] = $user->id;
						}
					}
					$order_id = DB::table('tb_order')->insertGetId([
						'order_date' => $params['order_date'],
						'customer_id' => $params['customer_id'],
						'receiver_phone' => $params['receiver_phone'],
						'address' => $params['address'],
						'total_amount' => $params['total_amount'],
						'discount_amount' => $params['discount_amount']??0,
						'grand_total' => $params['grand_total'],
						'screen_pay' => $params['screen_pay'] != null ? CRUDBooster::uploadFile2($request, 'screen_pay') : null,
						'pay_by' => $params['pay_by']??"Cash",
						'status_delivery' => $params['status_delivery']??"Preparing",
						'created_by' => $params['created_by']
					]);
					$insert_details = array();
					foreach ($params['order_items'] as $key => $value) {
						$insert_details[] = array(
							'order_id' => $order_id,
							'product_id' => $value['product_id']??NULL,
							'product_set_id' => $value['product_set_id']??NULL,
							'price' => $value['price'],
							'qty' => $value['qty'],
							'amount' => $value['amount'],
							'discount_amount' => $value['discount_amount']??0,
							'total_amount' => $value['total_amount']??0,
							'created_by' => $params['created_by']
						);
					}
					DB::table('tb_order_detail')->insert($insert_details);
					DB::commit();
					$data['api_status'] = 1;
					$data['api_message'] = 'success';
					$data['data'] = $user;
				} catch (\Exception $th) {
					DB::rollback();
					$data['api_message'] = 'something went wrong during create list order with detail '.$th;
					CRUDBooster::insertLog('error 101', $th);
				}

				finish:
				return response()->json($data, 200);
			}
			
			public function push_order() {
				$params = request('order_id');
				if ($params) {
					try {
						AdminOrdersController::sendMailOrder($params, 'new_order');
						AdminOrdersController::update_status($params, 'Preparing');
						return response()->json(['api_status'=>1,'api_message'=>'success'], 200);
					} catch (\Exception $th) {
						return response()->json(['api_status'=>0,'api_message'=>'failed to push '.$th], 200);
					}
				}
				
				return response()->json(['api_status'=>0,'api_message'=>'order_id field is required!'], 200);
			}

			public static function check_user_exist($phone) {
				if (isset($phone)) {
					$user = DB::table('cms_users')
						->where('phone', $phone)
						->first();
					if ($user) {
						return $user;
					}
				}
				return false;
			}

			public static function create_user($params) {
				$id = DB::table('cms_users')
					->insertGetId([
						'name' => $params['receiver_phone'],
						'phone' => $params['receiver_phone'],
						'id_cms_privileges' => 4,
						'address' => $params['address']
					]);
				return CRUDBooster::first('cms_users', $id);
			}

		}