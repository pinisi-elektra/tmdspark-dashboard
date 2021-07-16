<?php namespace App\Http\Controllers;

    use Session;
    use Request;
    use DB;
    use Cx;
    use Avatar;
    use Validator;
    use Carbon;
    use App\Models\User;

    class AdminStaffsController extends \crocodicstudio\crudbooster\controllers\CBController
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
            $this->table = "cms_users";
            # END CONFIGURATION DO NOT REMOVE THIS LINE

            # START COLUMNS DO NOT REMOVE THIS LINE
            $this->col = [];
            $this->col[] = ["label"=>"Photo","name"=>"photo","callback"=>function ($x) {
                $photo = Avatar::create($x->name);
                return '
				<a  data-lightbox="roadtrip" title="Photo: ' . $x->name . '" href="' . $photo . '">
					<img src="' . $photo . '" class="img-circle" alt="' . $x->name . '" width="40" height="40" />
				</a>';
            }];
            $this->col[] = ["label"=>"Nama","name"=>"name"];
            // $this->col[] = ["label"=>"Email","name"=>"email"];
            $this->col[] = ["label"=>"No. HP","name"=>"phone"];
            $this->col[] = ["label"=>"Parkiran","name"=>"parking_id", "join" => "lots,name"];
            $this->col[] = ["label"=>"Jam Mulai Kerja","name"=>"start", "callback_php" => '$row->start ? Carbon::parse($row->start)->format("H:i") : "-"'];
            $this->col[] = ["label"=>"Jam Selesai Kerja","name"=>"end", "callback_php" => '$row->end ? Carbon::parse($row->end)->format("H:i") : "-"'];
            $this->col[] = ["label"=>"Tempat Lahir","name"=>"pob", "join" => "cities,name"];
            $this->col[] = ["label"=>"Tgl. Lahir","name"=>"dob", "callback_php" => 'Carbon::parse($row->dob)->format("d/M/Y")'];

            $this->col[] = ["label"=>"NIP/NIK","name"=>"identity_no"];
            $this->col[] = ["label"=>"Foto Selfie + KTP","name"=>"selfie_photo","image" => 1];
            $this->col[] = ["label"=>"Foto KTP","name"=>"identity_photo","image" => 1];

            $this->col[] = ["label"=>"Terverifikasi","name"=>"verified_at", "callback_php" => '$row->verified_at ? "Ya" : "Belum"'];
            $this->col[] = ["label"=>"Status","name"=>"status"];
            $this->col[] = ["label"=>"Alasan Penolakan","name"=>"notes", "callback_php" => '$row->notes ?? "-"'];
            # END COLUMNS DO NOT REMOVE THIS LINE

            # START FORM DO NOT REMOVE THIS LINE
            $this->form = [];
            $this->form[] = ['label'=>'Nama','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:50','placeholder'=>'Anda hanya dapat memasukkan huruf saja'];
            // $this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:75|email|unique:cms_users,email'];
            $this->form[] = ['label'=>'No. HP','name'=>'phone','type'=>'number','validation'=>'required|numeric|regex:/^08[0-9]{8,11}$/','placeholder'=>'Dimulai dari 08XXXXXXXXXX, Contoh: 081612341234'];
            
            $where = "";
            if (Cx::me()->parking_id) {
                $where .= " AND id = " . Cx::me()->parking_id;
            }
            $this->form[] = ['label'=>'Parkiran','name'=>'parking_id','type'=>'select2','validation'=>'required|integer|exists:lots,id', "datatable" => "lots,name", "datatable_where" => "status = 'Active'" . $where, "datatable_format" => 'name," - ",address'];
            $this->form[] = ["label"=>"Tempat Lahir","name"=>"pob", "type" => "select2", 'validation'=>'required|integer|exists:cities,id', "datatable" => "cities,name"];
            $this->form[] = ["label"=>"Tgl. Lahir","name"=>"dob", "type" => "date", 'validation'=>'required|date_format:Y-m-d'];
            
            $this->form[] = ["label"=>"Jam Mulai Kerja","name"=>"start", "type" => "time", 'validation'=>'required|date_format:H:i'];
            $this->form[] = ["label"=>"Jam Selesai Kerja","name"=>"end", "type" => "time", 'validation'=>'required|date_format:H:i'];

            $this->form[] = ["label"=>"NIP/NIK","name"=>"identity_no", "type" => "text", 'validation'=>'required|string|min:3|unique:cms_users,identity_no'];
            $this->form[] = ["label"=>"Foto Selfie + KTP","name"=>"selfie_photo","type"=>"upload","help"=>"Foto selfie + ktp untuk verifikasi",'validation'=>'required|image|max:3000','encrypt' => 1];
            $this->form[] = ["label"=>"Foto KTP","name"=>"identity_photo","type"=>"upload","help"=>"Foto KTP untuk verifikasi",'validation'=>'required|image|max:3000','encrypt' => 1];
            
            $this->form[] = ["label"=>"Password","name"=>"password","type"=>"password","help"=>"Kosongkan jika tidak ada perubahan"];
            $this->form[] = ["label"=>"Konfirmasi Password","name"=>"password_confirmation","type"=>"password","help"=>"Kosongkan jika tidak ada perubahan"];
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
            if (in_array(Cx::myPrivilegeID(), [1, 2])) {
                $this->addaction[] = ["label" => "Terima", "url" => Cx::mainPath('set-accept/[id]'), "icon" => "fas fa-check", "color" => "success", "showIf" => "[status] == 'Inactive'", "confirmation" => true];
                $this->addaction[] = ["label" => "Tolak", "url" => "javascript:modalReject([id])", "icon" => "fas fa-times", "color" => "danger", "showIf" => "[status] == 'Inactive'"];
            }

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
            $this->table_row_color[] = ["condition" => "[status] == 'Inactive'", "color" => "warning"];
            $this->table_row_color[] = ["condition" => "[status] == 'Reject'", "color" => "danger"];
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
            $this->script_js = "
                function modalReject(id){
                    var modalReject = $('#modalReject');
                    modalReject.find('input[id=_token]').val('').val('" . csrf_token() . "');
                    modalReject.find('input[id=id]').val('').val(id);
                    modalReject.modal('show');
                }
            ";

            /*
            | ----------------------------------------------------------------------
            | Include HTML Code before index table
            | ----------------------------------------------------------------------
            | html code to display it before index table
            | $this->pre_index_html = "<p>test</p>";
            |
            */
            $this->pre_index_html = null;
            $this->modal_index_html = '
            <div class="modal fade" id="modalReject" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="' . Cx::mainPath('set-reject') . '" method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fas fa-plus"></i> Alasan Penolakan</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="_token"name="_token" readonly/>
                                <input type="hidden" id="id" name="id" class="form-control" placeholder="Masukkan ID" readonly/>

                                <div class="form-group">
                                    <label>Alasan<span class="text-danger" title="This field is required">*</span></label>
                                    <textarea id="notes" name="notes" style="resize: none;" class="form-control" rows="4" placeholder="Masukkan deskripsi pengiriman, seperti alamat, nama penerima, estimasi perkiraan sampai" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Proses</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            ';



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
            $query->where('id_cms_privileges', 5);

            if (Cx::me()->parking_id) {
                $query->where('parking_id', Cx::me()->parking_id);
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
            $postdata['status'] = 'Pending';
            $postdata['id_cms_privileges'] = 5;
            unset($postdata['password_confirmation']);
            if (Cx::me()->parking_id) {
                $postdata['parking_id'] = Cx::me()->parking_id;
            }

            if (isset($postdata['start']) && isset($postdata['end'])) {
                $start = Carbon::parse($postdata['start']);
                $end = Carbon::parse($postdata['end']);

                if ($end < $start) {
                    return redirect()->back()->with('warning', "Jam selesai kerja harus lebih besar dari jam masuk kerja")->send();
                    exit;
                }
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
            #Check Ownership
            if (Cx::me()->parking_id && Req::find($id)->parking_id != Cx::me()->parking_id) {
                Cx::redirect(Cx::mainPath(), "Anda tidak bisa mengubah user ini!");
            }

            if (isset($postdata['start']) && isset($postdata['end'])) {
                $start = Carbon::parse($postdata['start']);
                $end = Carbon::parse($postdata['end']);

                if ($end < $start) {
                    return redirect()->back()->with('warning', "Jam selesai kerja harus lebih besar dari jam masuk kerja")->send();
                    exit;
                }
            }

            //Your code here
            unset($postdata['password_confirmation']);
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
            #Check Ownership
            if (Cx::me()->parking_id && Req::find($id)->parking_id != Cx::me()->parking_id) {
                Cx::redirect(Cx::mainPath(), "Anda tidak bisa menghapus user ini!");
            }
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
        public function getSetAccept($id)
        {
            if (!in_array(Cx::myPrivilegeID(), [1, 2])) {
                Cx::redirect(Cx::adminPath(), cbLang("denied_access"));
            }

            $this->cbInit();

            $data = User::find($id);
            if (!$data) {
                Cx::redirect(Cx::mainPath(), cbLang("denied_access"));
            }

            $data->update([
                'status' => 'Active',
                'notes' => null,
                'verified_at' => now()
            ]);

            return redirect()->back()->with('success', 'Data petugas telah diverifikasi');
        }

        public function postSetReject()
        {
            if (!in_array(Cx::myPrivilegeID(), [1, 2])) {
                Cx::redirect(Cx::adminPath(), cbLang("denied_access"));
            }

            $validator = Validator::make(request()->only('id', 'notes'), [
                'id' => 'required|exists:cms_users,id',
                'notes' => 'required|string|max:5000',
            ]);

            $validator->setAttributeNames([
                'id' => 'Petugas',
                'notes' => 'Alasan penolakan'
            ]);

            if ($validator->fails()) {
                return redirect(Cx::mainPath())->with('warning', $validator->messages()->all()[0])->withInput();
            }

            $id = request('id');
            $notes = request('notes');

            User::find($id)->update([
                'notes' => nl2br($notes),
                'status' => "Reject"
            ]);

            return redirect(Cx::mainPath())->with('success', 'Data petugas telah ditolak');
        }
    }
