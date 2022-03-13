<?php namespace App\Http\Controllers;

    use Session;
    use Request;
    use DB;
    use Cx;
    use Carbon;
    use Str;
    use App\Models\Pricing;
    use Storage;

    class AdminOpenTransactionsController extends \crocodicstudio\crudbooster\controllers\CBController
    {
        public function cbInit()
        {

            # START CONFIGURATION DO NOT REMOVE THIS LINE
            $this->title_field = "bill_no";
            $this->limit = "1000";
            $this->orderby = "start_at,desc";
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
            $this->button_export = false;
            $this->table = "open_transactions";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"Foto Masuk","name"=>"photo_check_in", "callback" => function($q){
                $img = "https://api.tmdspark.co.id/storage/" . $q->photo_check_in;
                $file_headers = @get_headers($img);
                if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
                    $img = 'https://spark.pinisi-elektra.com/assets/images/no-image.jpg';
                } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
                    $img = 'https://spark.pinisi-elektra.com/assets/images/no-image.jpg';
                }
                
                return '<a data-lightbox="roadtrip" rel="group_{open_transactions}" title="Foto Masuk: 21072710562101" href="' . $img . '"><img width="40px" height="40px" src="' . $img . '"></a>';
            }];
            $this->col[] = ["label"=>"Waktu Mulai","name"=>"start_at", "callback_php" => 'Carbon::parse($row->start_at)->format("d/M/Y H:i")'];
            $this->col[] = ["label"=>"Parkiran","name"=>"parking_id","join"=>"lots,name", "filter" => 1, "callback" => function($q){
                $string = $q->lots_name . " (" . ucwords($q->lots_parking_type) . ")";
                return $string;
            }];
            $this->col[] = ["label"=>"No. Plat","name"=>"license_plate", "callback_php" => '$row->license_plate ?? "-"'];
            $this->col[] = ["label"=>"Kategori Kendaraan","name"=>"category_id","join"=>"categories,name", "filter" => 1];
            $this->col[] = ["label"=>"No. Bill","name"=>"bill_no"];
            $this->col[] = ["label"=>"Harga","name"=>"price", "callback_php" => '"Rp". number_format($row->price)'];
            $this->col[] = ["label"=>"Jenis Tarif","name"=>"pricing_type", "filter" => 1, "callback_php" => 'ucwords($row->pricing_type)'];
            // $this->col[] = ["label"=>"Waktu Berjalan","name"=>"id","callback" => function($q){
            //     return "<span class='elapsed_time' data-time='" . Carbon::parse($q->start_at)->getPreciseTimestamp(3) . "'>.Loading</span>";
            // }];
            $this->col[] = ["label"=>"Petugas","name"=>"staff_id","join"=>"cms_users,name", "callback_php" => '$row->staff_id ? $row->cms_users_name : "<b>Auto Gate</b>"'];
            $this->col[] = ["label"=>"Alat","name"=>"device_id","join"=>"devices,device_code"];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            $this->form[] = ['label'=>'No. Bill','name'=>'bill_no','type'=>'text','validation'=>'required|min:1|max:20', 'readonly' => 1, "value" => now()->format("Ymd") . strtoupper(Str::random(8))];
            $where = "";
            if (Cx::me()->parking_id) {
                $where .= " AND id = " . Cx::me()->parking_id;
            }
            $this->form[] = ['label'=>'Parkiran','name'=>'parking_id','type'=>'select2','validation'=>'required|integer|exists:lots,id', "datatable" => "lots,name", "datatable_where" => "status = 'Active'" . $where, "datatable_format" => 'name," - ",address'];
            $this->form[] = ['label'=>'Alat','name'=>'device_id','type'=>'select','validation'=>'required|integer|exists:devices,id','datatable'=>'devices,device_code', "parent_select" => "parking_id"];
            $this->form[] = ['label'=>'Kategori Kendaraan','name'=>'category_id','type'=>'select2','validation'=>'required|integer|exists:categories,id','datatable'=>'categories,name'];
            $this->form[] = ['label'=>'No. Plat','name'=>'license_plate','type'=>'text','validation'=>'required|min:1|max:25'];
            $this->form[] = ["label"=>"Jam Mulai","name"=>"start_at", "type" => "datetime", 'validation'=>'required|date_format:Y-m-d H:i:s', "value" => now()];
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
            // $this->script_js = "
            //     $(window).load(function(){
            //         function pad(num, size) {
            //             num = num.toString();
            //             while (num.length < size) num = '0' + num;
            //             return num;
            //         }

            //         $('.elapsed_time').each(function(){
            //             var obj = $(this);
            //             setInterval(function() {
            //                 var timespan = countdown(new Date(obj.data('time')), new Date());
            //                 var val = pad(timespan.hours,2) + ':' + pad(timespan.minutes, 2) + ':' + pad(timespan.seconds, 2);
            //                 obj.html(val);
            //             }, 1000);
            //         });
            //     });
            // ";


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
            $this->load_js[] = asset("assets/js/countdown.min.js");



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
            $pricing = Pricing::where([
                'parking_id' => $postdata['parking_id'],
                'category_id' => $postdata['category_id'],
            ])->first();

            $postdata['pricing_type'] = $pricing->pricing_type;
            $postdata['price'] = $pricing->price;
            $postdata['staff_id'] = 6;
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
