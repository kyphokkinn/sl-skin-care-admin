<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use URL;
	use App\OrderModel;
	use App\Http\Controllers\AdminPushnotificationsController;

	class AdminOrdersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "driver_name";
			$this->limit = "10";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "tb_order";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Order ID","name"=>"id"];
			$this->col[] = ["label"=>"Order Date","name"=>"order_date"];
			$this->col[] = ["label"=>"Customer","name"=>"customer_id","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Receiver Phone","name"=>"receiver_phone"];
			$this->col[] = ["label"=>"Address","name"=>"address"];
			$this->col[] = ["label"=>"Total Amount","name"=>"total_amount"];
			$this->col[] = ["label"=>"Grand Total","name"=>"grand_total"];
			$this->col[] = ["label"=>"Screen Pay","name"=>"screen_pay","image"=>true];
			$this->col[] = ["label"=>"Status Payment","name"=>"status_payment"];
			$this->col[] = ["label"=>"Status Delivery","name"=>"status_delivery"];
			$this->col[] = ["label"=>"Pay By","name"=>"pay_by"];
			$this->col[] = ["label"=>"Driver Phone","name"=>"driver_phone"];
			$this->col[] = ["label"=>"Is Cancel","name"=>"is_cancel"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Order Date','name'=>'order_date','type'=>'datetime','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Customer','name'=>'customer_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cms_users,name','datatable_where'=>'id_cms_privileges=4'];
			$this->form[] = ['label'=>'Receiver Phone','name'=>'receiver_phone','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Receiver Name','name'=>'receiver_name','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Delivery Fee','name'=>'delivery_id','type'=>'select2','width'=>'col-sm-10', 'datatable'=>'tb_delivery_fee,title', 'datatable_format'=>'title,\' | $\',fee'];
			$this->form[] = ['label'=>'Total Amount','name'=>'total_amount','type'=>'text','validation'=>'required|numeric','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Discount Amount','name'=>'discount_amount','type'=>'text','validation'=>'numeric','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'text','validation'=>'required|numeric','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Screen Pay','name'=>'screen_pay','type'=>'upload','validation'=>'image','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status Payment','name'=>'status_payment','type'=>'select','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'Unpaid;Paid'];
			$this->form[] = ['label'=>'Status Delivery','name'=>'status_delivery','type'=>'select','width'=>'col-sm-10','dataenum'=>'Pending;Confirmed;On Delivery;Delivered'];
			$this->form[] = ['label'=>'Pay By','name'=>'pay_by','type'=>'radio','validation'=>'required|min:1|max:255','width'=>'col-sm-10','dataenum'=>'Cash On Delivery;E-Cash'];
			$this->form[] = ['label'=>'Bank','name'=>'payment_id','type'=>'image','width'=>'col-sm-5','datatable'=>'tb_payment_method,photo'];
			$this->form[] = ['label'=>'Note','name'=>'note','type'=>'textarea','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Driver Phone','name'=>'driver_phone','type'=>'text','validation'=>'max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Driver Name','name'=>'driver_name','type'=>'text','validation'=>'max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Is Cancel','name'=>'is_cancel','type'=>'radio','validation'=>'required|string','width'=>'col-sm-10','dataenum'=>'No;Yes'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Order Date","name"=>"order_date","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer Id","name"=>"customer_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"customer,id"];
			//$this->form[] = ["label"=>"Receiver Phone","name"=>"receiver_phone","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Address","name"=>"address","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Total Amount","name"=>"total_amount","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Discount Amount","name"=>"discount_amount","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Grand Total","name"=>"grand_total","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Screen Pay","name"=>"screen_pay","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status Payment","name"=>"status_payment","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status Delivery","name"=>"status_delivery","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Pay By","name"=>"pay_by","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Note","name"=>"note","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Driver Phone","name"=>"driver_phone","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Driver Name","name"=>"driver_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Is Cancel","name"=>"is_cancel","type"=>"radio","required"=>TRUE,"validation"=>"required|integer","dataenum"=>"Array"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array(
				['label'=>'Pending', 'url'=>CRUDBooster::mainPath('?filter_column%5Btb_order.order_date%5D%5Btype%5D=&filter_column%5Btb_order.order_date%5D%5Bsorting%5D=&filter_column%5Bcms_users.name%5D%5Btype%5D=&filter_column%5Bcms_users.name%5D%5Bsorting%5D=&filter_column%5Btb_order.receiver_phone%5D%5Btype%5D=&filter_column%5Btb_order.receiver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.address%5D%5Btype%5D=&filter_column%5Btb_order.address%5D%5Bsorting%5D=&filter_column%5Btb_order.total_amount%5D%5Btype%5D=&filter_column%5Btb_order.total_amount%5D%5Bsorting%5D=&filter_column%5Btb_order.grand_total%5D%5Btype%5D=&filter_column%5Btb_order.grand_total%5D%5Bsorting%5D=&filter_column%5Btb_order.status_payment%5D%5Btype%5D=&filter_column%5Btb_order.status_payment%5D%5Bsorting%5D=&filter_column%5Btb_order.status_delivery%5D%5Btype%5D=%3D&filter_column%5Btb_order.status_delivery%5D%5Bvalue%5D=Pending&filter_column%5Btb_order.status_delivery%5D%5Bsorting%5D=&filter_column%5Btb_order.pay_by%5D%5Btype%5D=&filter_column%5Btb_order.pay_by%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_name%5D%5Btype%5D=&filter_column%5Btb_order.driver_name%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_phone%5D%5Btype%5D=&filter_column%5Btb_order.driver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.is_cancel%5D%5Btype%5D=&filter_column%5Btb_order.is_cancel%5D%5Bsorting%5D=&filter_column%5Btb_order.created_at%5D%5Btype%5D=&filter_column%5Btb_order.created_at%5D%5Bsorting%5D=&lasturl=https%3A%2F%2Fwww.lorngdysl.com%2Fadmin%2Forders')],
				['label'=>'Unpaid', 'url'=>CRUDBooster::mainPath('?filter_column%5Btb_order.order_date%5D%5Btype%5D=&filter_column%5Btb_order.order_date%5D%5Bsorting%5D=&filter_column%5Bcms_users.name%5D%5Btype%5D=&filter_column%5Bcms_users.name%5D%5Bsorting%5D=&filter_column%5Btb_order.receiver_phone%5D%5Btype%5D=&filter_column%5Btb_order.receiver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.address%5D%5Btype%5D=&filter_column%5Btb_order.address%5D%5Bsorting%5D=&filter_column%5Btb_order.total_amount%5D%5Btype%5D=&filter_column%5Btb_order.total_amount%5D%5Bsorting%5D=&filter_column%5Btb_order.grand_total%5D%5Btype%5D=&filter_column%5Btb_order.grand_total%5D%5Bsorting%5D=&filter_column%5Btb_order.status_payment%5D%5Btype%5D=%3D&filter_column%5Btb_order.status_payment%5D%5Bvalue%5D=Unpaid&filter_column%5Btb_order.status_payment%5D%5Bsorting%5D=&filter_column%5Btb_order.status_delivery%5D%5Btype%5D=&filter_column%5Btb_order.status_delivery%5D%5Bsorting%5D=&filter_column%5Btb_order.pay_by%5D%5Btype%5D=&filter_column%5Btb_order.pay_by%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_name%5D%5Btype%5D=&filter_column%5Btb_order.driver_name%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_phone%5D%5Btype%5D=&filter_column%5Btb_order.driver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.is_cancel%5D%5Btype%5D=&filter_column%5Btb_order.is_cancel%5D%5Bsorting%5D=&filter_column%5Btb_order.created_at%5D%5Btype%5D=&filter_column%5Btb_order.created_at%5D%5Bsorting%5D=&lasturl=https%3A%2F%2Fwww.lorngdysl.com%2Fadmin%2Forders')],
				['label'=>'Paid', 'url'=>CRUDBooster::mainPath('?filter_column%5Btb_order.order_date%5D%5Btype%5D=&filter_column%5Btb_order.order_date%5D%5Bsorting%5D=&filter_column%5Bcms_users.name%5D%5Btype%5D=&filter_column%5Bcms_users.name%5D%5Bsorting%5D=&filter_column%5Btb_order.receiver_phone%5D%5Btype%5D=&filter_column%5Btb_order.receiver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.address%5D%5Btype%5D=&filter_column%5Btb_order.address%5D%5Bsorting%5D=&filter_column%5Btb_order.total_amount%5D%5Btype%5D=&filter_column%5Btb_order.total_amount%5D%5Bsorting%5D=&filter_column%5Btb_order.grand_total%5D%5Btype%5D=&filter_column%5Btb_order.grand_total%5D%5Bsorting%5D=&filter_column%5Btb_order.status_payment%5D%5Btype%5D=%3D&filter_column%5Btb_order.status_payment%5D%5Bvalue%5D=Paid&filter_column%5Btb_order.status_payment%5D%5Bsorting%5D=&filter_column%5Btb_order.status_delivery%5D%5Btype%5D=&filter_column%5Btb_order.status_delivery%5D%5Bsorting%5D=&filter_column%5Btb_order.pay_by%5D%5Btype%5D=&filter_column%5Btb_order.pay_by%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_name%5D%5Btype%5D=&filter_column%5Btb_order.driver_name%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_phone%5D%5Btype%5D=&filter_column%5Btb_order.driver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.is_cancel%5D%5Btype%5D=&filter_column%5Btb_order.is_cancel%5D%5Bsorting%5D=&filter_column%5Btb_order.created_at%5D%5Btype%5D=&filter_column%5Btb_order.created_at%5D%5Bsorting%5D=&lasturl=https%3A%2F%2Fwww.lorngdysl.com%2Fadmin%2Forders')],
				['label'=>'Cash', 'url'=>CRUDBooster::mainPath('?filter_column%5Btb_order.order_date%5D%5Btype%5D=&filter_column%5Btb_order.order_date%5D%5Bsorting%5D=&filter_column%5Bcms_users.name%5D%5Btype%5D=&filter_column%5Bcms_users.name%5D%5Bsorting%5D=&filter_column%5Btb_order.receiver_phone%5D%5Btype%5D=&filter_column%5Btb_order.receiver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.address%5D%5Btype%5D=&filter_column%5Btb_order.address%5D%5Bsorting%5D=&filter_column%5Btb_order.total_amount%5D%5Btype%5D=&filter_column%5Btb_order.total_amount%5D%5Bsorting%5D=&filter_column%5Btb_order.grand_total%5D%5Btype%5D=&filter_column%5Btb_order.grand_total%5D%5Bsorting%5D=&filter_column%5Btb_order.status_payment%5D%5Btype%5D=&filter_column%5Btb_order.status_payment%5D%5Bsorting%5D=&filter_column%5Btb_order.status_delivery%5D%5Btype%5D=&filter_column%5Btb_order.status_delivery%5D%5Bsorting%5D=&filter_column%5Btb_order.pay_by%5D%5Btype%5D=%3D&filter_column%5Btb_order.pay_by%5D%5Bvalue%5D=Cash&filter_column%5Btb_order.pay_by%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_name%5D%5Btype%5D=&filter_column%5Btb_order.driver_name%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_phone%5D%5Btype%5D=&filter_column%5Btb_order.driver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.is_cancel%5D%5Btype%5D=&filter_column%5Btb_order.is_cancel%5D%5Bsorting%5D=&filter_column%5Btb_order.created_at%5D%5Btype%5D=&filter_column%5Btb_order.created_at%5D%5Bsorting%5D=&lasturl=https%3A%2F%2Fwww.lorngdysl.com%2Fadmin%2Forders')],
				['label'=>'Cash On Delivery', 'url'=>CRUDBooster::mainPath('?filter_column%5Btb_order.order_date%5D%5Btype%5D=&filter_column%5Btb_order.order_date%5D%5Bsorting%5D=&filter_column%5Bcms_users.name%5D%5Btype%5D=&filter_column%5Bcms_users.name%5D%5Bsorting%5D=&filter_column%5Btb_order.receiver_phone%5D%5Btype%5D=&filter_column%5Btb_order.receiver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.address%5D%5Btype%5D=&filter_column%5Btb_order.address%5D%5Bsorting%5D=&filter_column%5Btb_order.total_amount%5D%5Btype%5D=&filter_column%5Btb_order.total_amount%5D%5Bsorting%5D=&filter_column%5Btb_order.grand_total%5D%5Btype%5D=&filter_column%5Btb_order.grand_total%5D%5Bsorting%5D=&filter_column%5Btb_order.status_payment%5D%5Btype%5D=&filter_column%5Btb_order.status_payment%5D%5Bsorting%5D=&filter_column%5Btb_order.status_delivery%5D%5Btype%5D=&filter_column%5Btb_order.status_delivery%5D%5Bsorting%5D=&filter_column%5Btb_order.pay_by%5D%5Btype%5D=%3D&filter_column%5Btb_order.pay_by%5D%5Bvalue%5D=Cash+On+Delivery&filter_column%5Btb_order.pay_by%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_name%5D%5Btype%5D=&filter_column%5Btb_order.driver_name%5D%5Bsorting%5D=&filter_column%5Btb_order.driver_phone%5D%5Btype%5D=&filter_column%5Btb_order.driver_phone%5D%5Bsorting%5D=&filter_column%5Btb_order.is_cancel%5D%5Btype%5D=&filter_column%5Btb_order.is_cancel%5D%5Bsorting%5D=&filter_column%5Btb_order.created_at%5D%5Btype%5D=&filter_column%5Btb_order.created_at%5D%5Bsorting%5D=&lasturl=https%3A%2F%2Fwww.lorngdysl.com%2Fadmin%2Forders')]
			);



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			switch ($column_index) {
				case 1:
					if ($column_value) {
						$column_value = "<a target=\"_blank\" href=".CRUDBooster::adminPath('order_details?filter_column%5Btb_order.id%5D%5Btype%5D=%3D&filter_column%5Btb_order.id%5D%5Bvalue%5D='.$column_value.'&filter_column%5Btb_order.id%5D%5Bsorting%5D=&filter_column%5Btb_product.title%5D%5Btype%5D=&filter_column%5Btb_product.title%5D%5Bsorting%5D=&filter_column%5Btb_order_detail.qty%5D%5Btype%5D=&filter_column%5Btb_order_detail.qty%5D%5Bsorting%5D=&filter_column%5Btb_order_detail.price%5D%5Btype%5D=&filter_column%5Btb_order_detail.price%5D%5Bsorting%5D=&filter_column%5Btb_order_detail.amount%5D%5Btype%5D=&filter_column%5Btb_order_detail.amount%5D%5Bsorting%5D=&filter_column%5Btb_order_detail.total_amount%5D%5Btype%5D=&filter_column%5Btb_order_detail.total_amount%5D%5Bsorting%5D=&filter_column%5Bcms_users.name%5D%5Btype%5D=&filter_column%5Bcms_users.name%5D%5Bsorting%5D=&filter_column%5Btb_order_detail.created_at%5D%5Btype%5D=&filter_column%5Btb_order_detail.created_at%5D%5Bsorting%5D=&lasturl=https%3A%2F%2Fwww.lorngdysl.com%2Fadmin%2Forder_details').">$column_value</a>";
					}
					break;
			}
		}

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
			$postdata['created_by'] = CRUDBooster::myId();

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here
			self::update_status($id, 'Pending');
			self::sendMailOrder($id, 'new_order');
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
			$item = CRUDBooster::first($this->table, $id);
			if($postdata['status_delivery'] != $item->status_delivery) {
				self::update_status($id, $postdata['status_delivery']);
			}
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 
			self::sendMailOrder($id, 'update_order');
			if ($this->status_change) {
				
			}
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public static function update_status($id, $status) {
			$item = CRUDBooster::first('tb_order', $id);
			$insert = null;
			switch ($status) {
				case 'On Delivery':
					$insert = [
						'title' => 'ការបញ្ជាទិញលេខ #'.$id.' ត្រូវបានដឹកចេញតាមផ្លូវ',
						'content' => 'ការកម្មង់របស់លោកអ្នកត្រូវបាន រៀបចំដឹកចេញទៅហើយ សូមមេត្តារង់ចាំ អ្នកដឹករបស់យើងឈ្មោះ ៖ '.$item->driver_name.' លេខទូរស័ព្ទ ៖ '.$item->driver_phone.' នឹងទំនាក់ទំនងទៅលោកអ្នកក្នុងពេលឆាប់ៗនេះ​ ។ សូមអរគុណ'
					];
					break;
				case 'Delivered':
					$insert = [
						'title' => 'ការបញ្ជាទិញលេខ #'.$id.' ត្រូវបានដឹកដល់គោលដៅ',
						'content' => 'ការកម្មង់របស់លោកអ្នកត្រូវបាន បញ្ជូនដល់គោលដៅ សូមអរគុណសម្រាប់ការ កម្មង់របស់លោកអ្នក ជូនពរសំណាងល្អ ។'
					];
					break;
				case 'Pending':
					$insert = [
						'title' => 'ការបញ្ជាទិញលេខ #'.$id.' ត្រូវបានដាក់ស្នើរ ក្រុមការងារនឹងរៀបចំឆាប់នេះ',
						'content' => 'ការកម្មង់របស់លោកអ្នកត្រូវបាន ត្រូវបានកំពុងត្រួតពិនិត្យ សូមអរគុណសម្រាប់ការ កម្មង់របស់លោកអ្នក ជូនពរសំណាងល្អ ។'
					];
					break;
				case 'Confirmed':
						$insert = [
							'title' => 'ការបញ្ជាទិញលេខ #'.$id.' ត្រូវបាន Confirmed  ក្រុមការងារនឹងរៀបចំឆាប់នេះ',
							'content' => 'ការកម្មង់របស់លោកអ្នកត្រូវបាន ត្រូវបានកំពុងត្រួតពិនិត្យ សូមអរគុណសម្រាប់ការ កម្មង់របស់លោកអ្នក ជូនពរសំណាងល្អ ។'
						];
					break;
			}
			if (in_array($status, ['Pending',"Confirmed", 'Delivered', 'On Delivery'])) {
				$insert['is_all'] = 'No';
				$insert['user_id'] = $item->customer_id;
				$insert['user_id_list'] = $item->customer_id;
				$insert['created_by'] = $item->customer_id;

				$notification_id = DB::table('tb_notification')->insertGetId($insert);
				AdminPushnotificationsController::push_notification($notification_id);
			}
		}

		public static function sendMailOrder($id, $template) {
			$item = OrderModel::find($id);
			// $item = OrderModel::find(87);
			$item->detail = "";
			foreach($item->orderItems as $key => $detail) {
				$title = "";
				if ($detail->product) {
					$title = $detail->product->title;
				}
				if($detail->promotion) {
					$title = $detail->promotion->title;
				}
				$item->detail .= ($key+1)." | ឈ្មោះ ៖ ". $title . ' | ចំនួន ៖ '.$detail->qty . '<br>';
			}
			if ($item->screen_pay) {
				$item->screen_pay = URL::to($item->screen_pay);
			}
			CRUDBooster::sendEmail(['to' => 'chingc035@gmail.com', 'data' => collect($item)->all(), 'template'=> $template]);
		}

	    //By the way, you can still create your own method in here... :) 


	}