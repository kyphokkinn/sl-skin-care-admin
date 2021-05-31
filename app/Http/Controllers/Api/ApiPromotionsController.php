<?php namespace App\Http\Controllers\Api;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiPromotionsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "tb_promotion";        
				$this->permalink   = "promotions";    
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
					$result['data'] = $result['data']->map(function($item,$key){
						$item->description_text = trim(strip_tags($item->description));
						return $item;
					});
					if (!empty($postdata['id']) && count($result['data']) > 0) {
						$result['data'][0]->products = DB::table('tb_promotion_has_product')
							->selectRaw("tb_product.*,tb_promotion_has_product.qty as promote_qty")
							->join("tb_product", "tb_product.id", "tb_promotion_has_product.product_id")
							->whereNull('tb_product.deleted_at')
							->whereNull('tb_promotion_has_product.deleted_at')
							->where("tb_promotion_has_product.promotion_id", $postdata['id'])
							->get();
						if ($result['data'][0]->products->count() > 0) {
							$result['data'][0]->products->map(function($item) {
								if ($item->image_path) {
									$item->image_path = url($item->image_path);
								}
								return $item;
							});
						}
					}
				}
		    }

		}