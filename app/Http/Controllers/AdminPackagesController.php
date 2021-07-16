<?php namespace App\Http\Controllers;

    use Session;
    use Request;
    use DB;
    use Cx;
    use Validator;
    use App\Models\Lot;
    use App\Models\Category;
    use App\Models\Pricing;

    class AdminPackagesController extends \crocodicstudio\crudbooster\controllers\CBController
    {
        public function cbInit()
        {

            # START CONFIGURATION DO NOT REMOVE THIS LINE
            $this->title_field = "name";
            $this->limit = "100";
            $this->orderby = ["parking_id" => "asc", "val" => "asc"];
            $this->global_privilege = false;
            $this->button_table_action = true;
            $this->button_bulk_action = false;
            $this->button_action_style = "button_icon";
            $this->button_add = true;
            $this->button_edit = true;
            $this->button_delete = true;
            $this->button_detail = true;
            $this->button_show = true;
            $this->button_filter = true;
            $this->button_import = false;
            $this->button_export = true;
            $this->table = "packages";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"Parkiran","name"=>"parking_id", "join" => "lots,name"];
            $this->col[] = ["label"=>"Nama Paket","name"=>"name"];
            $this->col[] = ["label"=>"Hari","name"=>"val", "callback_php" => 'number_format($row->val) . " Hari"'];
            $this->col[] = ["label"=>"Harga","name"=>"price", "callback_php" => '"Rp". number_format($row->price)'];
            $this->col[] = ["label"=>"Status","name"=>"status"];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            $where = "";
            if (Cx::me()->parking_id) {
                $where .= " AND id = " . Cx::me()->parking_id;
            }
            $this->form[] = ['label'=>'Parkiran','name'=>'parking_id','type'=>'select2','validation'=>'required|integer|exists:lots,id','width'=>'col-sm-10', "datatable" => "lots,name", "datatable_where" => "status = 'Active'" . $where, "datatable_format" => 'name," - ",address', "exception" => in_array(Cx::myPrivilegeID(), [1, 2]) ? 0 : 1, "disabled" => in_array(Cx::myPrivilegeID(), [1, 2]) ? 0 : 1];
            $this->form[] = ['label'=>'Nama Paket','name'=>'val','type'=>'select2','validation'=>'required|integer|in:7,30,365','width'=>'col-sm-10','dataenum'=>'7|Mingguan (7 Hari);30|Bulanan (30 Hari);365|Tahunan (365 Hari)'];
            $this->form[] = ['label'=>'Harga','name'=>'price','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Status','name'=>'status','type'=>'select2','validation'=>'required|min:1|max:10','width'=>'col-sm-10','dataenum'=>'Active;Inactive', 'value' => 'Active'];
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
            $this->addaction[] = ["label" => "Area", "url" => Cx::mainPath('set-draw-area/[id]'), "icon" => "fas fa-globe", "color" => "success"];

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
            //Your code here
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
            #Check if exists;
            $exists = DB::table($this->table)->where([
                'parking_id' => $postdata['parking_id'],
                'val' => $postdata['name'],
            ])->first();

            if($exists){
                Cx::redirect(Cx::mainpath('add'), "Paket sudah tersedia sebelumnya!", 'warning');
            }

            $postdata['val'] = $postdata['name'];

            switch($postdata['name']) {
                case 7 : $postdata['name'] = "Mingguan (7 Hari)"; break;
                case 30 : $postdata['name'] = "Bulanan (30 Hari)"; break;
                case 365 : $postdata['name'] = "Tahunan (365 Hari)"; break;
            }

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
            #Check if exists;
            $exists = DB::table($this->table)->where([
                'parking_id' => $postdata['parking_id'],
                'val' => $postdata['name'],
            ])->first();

            if($exists){
                Cx::redirect(Cx::mainpath('edit/' . $id), "Paket sudah tersedia sebelumnya!", 'warning');
            }

            $postdata['val'] = $postdata['name'];

            switch($postdata['name']) {
                case 7 : $postdata['name'] = "Mingguan (7 Hari)"; break;
                case 30 : $postdata['name'] = "Bulanan (30 Hari)"; break;
                case 365 : $postdata['name'] = "Tahunan (365 Hari)"; break;
            }
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
    }
