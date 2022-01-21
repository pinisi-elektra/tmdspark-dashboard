<?php namespace App\Http\Controllers;

    use Session;
    use Request;
    use DB;
    use Cx;
    use Validator;
    use App\Models\Lot;
    use App\Models\Category;
    use App\Models\Pricing;

    class AdminLotsController extends \crocodicstudio\crudbooster\controllers\CBController
    {
        public function cbInit()
        {

            # START CONFIGURATION DO NOT REMOVE THIS LINE
            $this->title_field = "name";
            $this->limit = "100";
            $this->orderby = "name,asc";
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
            $this->table = "lots";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"Nama","name"=>"name"];
            $this->col[] = ["label"=>"Jenis Parkiran","name"=>"parking_type", "callback_php" => 'ucwords($row->parking_type)'];
            $this->col[] = ["label"=>"Kapasitas Parkir","name"=>"slots", "callback_php" => 'number_format($row->slots)'];
            $this->col[] = ["label"=>"Jenis Tarif","name"=>"pricing_type", "callback_php" => 'ucwords($row->pricing_type)'];
            $this->col[] = ["label"=>"Alamat","name"=>"address"];
            $this->col[] = ["label"=>"Provinsi","name"=>"province_id","join"=>"provinces,name"];
            $this->col[] = ["label"=>"Kota/Kabupaten","name"=>"city_id","join"=>"cities,name"];
            $this->col[] = ["label"=>"Kecamatan","name"=>"district_id","join"=>"districts,name"];
            $this->col[] = ["label"=>"Status","name"=>"status"];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            $this->form[] = ['label'=>'Nama','name'=>'name','type'=>'text','validation'=>'required|regex:/^[0-9a-zA-Z\s]*$/|min:3|max:70','width'=>'col-sm-10','placeholder'=>'Anda hanya dapat memasukkan huruf saja'];
            $this->form[] = ['label'=>'Jenis Parkiran','name'=>'parking_type','type'=>'select2','validation'=>'required|string|in:street,gate','width'=>'col-sm-10','dataenum'=>'street|Street Parking;gate|Gate System', 'value' => 'street'];
            $this->form[] = ['label'=>'Kapasitas Parkir','name'=>'slots','type'=>'money','validation'=>'required|integer|min:0'];
            $this->form[] = ['label'=>'Jenis Tarif','name'=>'pricing_type','type'=>'select2','validation'=>'required|string|in:fixed,hourly','width'=>'col-sm-10','dataenum'=>'fixed|Fixed;hourly|Hourly', 'value' => 'hourly'];
            $this->form[] = ['label'=>'Provinsi','name'=>'province_id','type'=>'select','validation'=>'required|integer|exists:provinces,id','width'=>'col-sm-10','datatable'=>'provinces,name'];
            $this->form[] = ['label'=>'Kota/Kabupaten','name'=>'city_id','type'=>'select','validation'=>'required|integer|exists:cities,id','width'=>'col-sm-10','datatable'=>'cities,name', "parent_select" => "province_id"];
            $this->form[] = ['label'=>'Kecamatan','name'=>'district_id','type'=>'select','validation'=>'required|integer|exists:districts,id','width'=>'col-sm-10','datatable'=>'districts,name', "parent_select" => "city_id"];
            $this->form[] = ['label'=>'Alamat','name'=>'address','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
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
            $this->table_row_color = [];
            $this->table_row_color[] = ["condition" => "[status] == 'Inactive'", "color" => "danger"];
            $this->table_row_color[] = ["condition" => "[status] == 'Active'", "color" => "success"];



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
            $categories = Category::get();
            foreach($categories as $cat) {
                Pricing::firstOrCreate([
                    'category_id' => $cat->id,
                    'parking_id' => $id,
                ], [
                    'price' => 2000
                ]);
            }
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
            $categories = Category::get();
            foreach($categories as $cat) {
                Pricing::firstOrCreate([
                    'category_id' => $cat->id,
                    'parking_id' => $id,
                ], [
                    'price' => 2000
                ]);
            }
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

        public function getSetDrawArea($id) {
            if (!in_array(Cx::myPrivilegeID(), [1, 2])) {
                Cx::redirect(Cx::adminPath(), cbLang("denied_access"));
            }

            $data = [];
            $data['lot'] = Lot::findOrFail($id);
            $data['page_title'] = 'Gambar Area';
            return view('drawer', $data);
        }

        public function postAddGeojson()
        {
            if (!in_array(Cx::myPrivilegeID(), [1, 2])) {
                Cx::redirect(Cx::adminPath(), cbLang("denied_access"));
            }

            $validator = Validator::make(request()->only('id', 'geojson'), [
                'id' => 'required|exists:lots,id',
                'geojson' => 'required|string|max:10000',
            ]);

            $validator->setAttributeNames([
                'id' => 'Parkiran',
                'geojson' => 'Data Polygon'
            ]);

            if ($validator->fails()) {
                return redirect(Cx::mainPath('set-draw-area/' . request('id')))->with('warning', $validator->messages()->all()[0])->withInput();
            }   

            $id = request('id');
            $geojson = request('geojson');

            Lot::find($id)->update([
                'geojson' => $geojson,
            ]);

            return redirect(Cx::mainPath('set-draw-area/' . request('id')))->with('success', 'Data area telah diubah');
        }
    }
