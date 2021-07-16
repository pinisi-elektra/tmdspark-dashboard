<?php namespace App\Http\Controllers;

    use Session;
    use Request;
    use DB;
    use CRUDBooster;

    class AdminTestController extends \crocodicstudio\crudbooster\controllers\CBController
    {
        function reverseBytes($input) {
            $result = '';
            // first byte
            $result .= substr($input, 6, 2);
    
            // second byte
            $result .= substr($input, 4, 2);
    
            // third byte
            $result .= substr($input, 2, 2);
    
            // fourth byte
            $result .= substr($input, 0, 2);
    
            return $result;
        }

        function hex2float($data)
        {
            $binarydata32 = pack('H*',$this->reverseBytes(substr($data, 0, 8)));
            $altitude = unpack("V", $binarydata32);
        
            // Bytes from 5 to 8 are longitude value, in float format, bytes in
            //   reverse order
            
        
            echo 'altitude  : ' . $altitude[1]  . "\r\n";
            echo 'longitude : ' . $longitude[1] . "\r\n";
            echo 'latitude  : ' . $latitude[1]  . "\r\n";
                                  
            die;;
        }
        
        public function cbInit()
        {

            # START CONFIGURATION DO NOT REMOVE THIS LINE
            $this->title_field = "id";
            $this->limit = "20";
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
            $this->button_filter = true;
            $this->button_import = false;
            $this->button_export = false;
            $this->table = "test";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"Tgl","name"=>"created_at"];

            $this->col[] = ["label"=>"Test","name"=>"test", "callback" => function ($q) {
                $datas = json_decode($q->test);
                $string ="";
                foreach ($datas as $key => $data) {
                    $string .= "- " . $data . "<br>";

                }

                try {
                    $payload = $datas->data;
                    $source = substr($payload, 0, 2);
                    $lat = substr($payload, 4, 8);
                    $lng = substr($payload, 12, 8);
                    $battery_level = substr($payload, 20, 2);
                    $data_battery_level = substr($payload, 22, 2);
                    
                    $binarydata32 = pack('H*',$this->reverseBytes($lat));
                    $floatLat = unpack("f", $binarydata32)[1];
    
                    $binarydata32 = pack('H*',$this->reverseBytes($lng));
                    $floatLng = unpack("f", $binarydata32)[1];
                    // dd($payload, $source, $lat, $floatLat, $lng, $floatLng, $battery_level, $data_battery_level);
                    
                    $string .= "- Lat: " . $floatLat . "<br>";
                    $string .= "- Lng: " . $floatLng . "<br>";
    
                }
                catch(\Exception $e) {
                    $string .= "";
                }
                
                return $string;
            }];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            $this->form[] = ['label'=>'Test','name'=>'test','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
            # END FORM DO NOT REMOVE THIS LINE

            # OLD START FORM
            //$this->form = [];
            //$this->form[] = ["label"=>"Test","name"=>"test","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
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
        public function getTestMap()
        {
            $data['page_title'] = "Tampilan Titik Lokasi";
            return view('test-map', $data);
        }
    }
