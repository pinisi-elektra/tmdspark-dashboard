<?php namespace App\Http\Controllers;

    use Session;
    use Request;
    use DB;
    use Cx;

    class AdminClosedTransactionsController extends \crocodicstudio\crudbooster\controllers\CBController
    {
        public function cbInit()
        {

            # START CONFIGURATION DO NOT REMOVE THIS LINE
            $this->title_field = "parking_name";
            $this->limit = "1000";
            $this->orderby = "stop_at,desc";
            $this->global_privilege = false;
            $this->button_table_action = true;
            $this->button_bulk_action = false;
            $this->button_action_style = "button_icon";
            $this->button_add = false;
            $this->button_edit = false;
            $this->button_delete = false;
            $this->button_detail = false;
            $this->button_show = true;
            $this->button_filter = true;
            $this->button_import = false;
            $this->button_export = true;
            $this->table = "closed_transactions";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"No. Bill","name"=>"bill_no"];
            $this->col[] = ["label"=>"Parkiran","name"=>"parking_name", "filter" => 1];
            $this->col[] = ["label"=>"No. Plat","name"=>"license_plate"];
            $this->col[] = ["label"=>"Kategori Kendaraan","name"=>"vehicle_category_name", "filter" => 1];
            $this->col[] = ["label"=>"Harga","name"=>"price", "callback_php" => '"Rp" . number_format($row->price)'];
            $this->col[] = ["label"=>"Jenis Tarif","name"=>"pricing_type", "filter" => 1];
            $this->col[] = ["label"=>"Waktu Mulai","name"=>"start_at", "callback_php" => 'Carbon::parse($row->start_at)->format("d/M/Y H:i")'];
            $this->col[] = ["label"=>"Waktu Selesai","name"=>"stop_at", "callback_php" => 'Carbon::parse($row->stop_at)->format("d/M/Y H:i")'];
            $this->col[] = ["label"=>"Total Waktu","name"=>"elapsed_hours", "callback_php" => 'number_format($row->elapsed_hours) . " Jam"'];
            $this->col[] = ["label"=>"Total Tarif","name"=>"total", "callback_php" => '"Rp" . number_format($row->total)'];
            $this->col[] = ["label"=>"Alat","name"=>"device_code"];
            $this->col[] = ["label"=>"Petugas Awal","name"=>"open_staff_name"];
            $this->col[] = ["label"=>"Petugas Akhir","name"=>"close_staff_name"];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            // $this->form[] = ['label'=>'Bill No','name'=>'bill_no','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Device Id','name'=>'device_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'device,id'];
            // $this->form[] = ['label'=>'Category Id','name'=>'category_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'category,id'];
            // $this->form[] = ['label'=>'Parking Id','name'=>'parking_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'parking,id'];
            // $this->form[] = ['label'=>'Open Staff Id','name'=>'open_staff_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'open_staff,id'];
            // $this->form[] = ['label'=>'Close Staff Id','name'=>'close_staff_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'close_staff,id'];
            // $this->form[] = ['label'=>'License Plate','name'=>'license_plate','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Device Code','name'=>'device_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Parking Name','name'=>'parking_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Open Staff Name','name'=>'open_staff_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Close Staff Name','name'=>'close_staff_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Start At','name'=>'start_at','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Stop At','name'=>'stop_at','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Elapsed Hours','name'=>'elapsed_hours','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Pricing Type','name'=>'pricing_type','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Price','name'=>'price','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            // $this->form[] = ['label'=>'Total','name'=>'total','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
            # END FORM DO NOT REMOVE THIS LINE

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
            $this->index_button = array();



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
            $this->script_js = null;


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
            $this->style_css = null;



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
        public function actionButtonSelected($id_selected, $button_name)
        {
            //Your code here
        }


        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate query of index result
        | ----------------------------------------------------------------------
        | @query = current sql query
        |
        */
        public function hook_query_index(&$query)
        {
            if (Cx::me()->parking_id) {
                $query->where($this->table . '.parking_id', Cx::me()->parking_id);
            }

            if(request('parking_id')) {
                $query->where($this->table . '.parking_id', request('parking_id'));
            }
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate row of index table html
        | ----------------------------------------------------------------------
        |
        */
        public function hook_row_index($column_index, &$column_value)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate data input before add data is execute
        | ----------------------------------------------------------------------
        | @arr
        |
        */
        public function hook_before_add(&$postdata)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command after add public static function called
        | ----------------------------------------------------------------------
        | @id = last insert id
        |
        */
        public function hook_after_add($id)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate data input before update data is execute
        | ----------------------------------------------------------------------
        | @postdata = input post data
        | @id       = current id
        |
        */
        public function hook_before_edit(&$postdata, $id)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command after edit public static function called
        | ----------------------------------------------------------------------
        | @id       = current id
        |
        */
        public function hook_after_edit($id)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command before delete public static function called
        | ----------------------------------------------------------------------
        | @id       = current id
        |
        */
        public function hook_before_delete($id)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command after delete public static function called
        | ----------------------------------------------------------------------
        | @id       = current id
        |
        */
        public function hook_after_delete($id)
        {
            //Your code here
        }



        //By the way, you can still create your own method in here... :)
    }
