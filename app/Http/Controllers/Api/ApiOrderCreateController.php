<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

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
				$fields = ['receiver_phone', 'total_amount', 'grand_total', 'order_items', 'order_date', 'created_by'];
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
				} catch (\Exception $th) {
					DB::rollback();
					$data['api_message'] = 'something went wrong during create list order with detail';
				}

				finish:
				return response()->json($data, 200);
			}

		}