@extends('crudbooster::admin_template')
@section('content')
    @if($button_show_data || $button_reload_data || $button_new_data || $button_delete_data || $index_button || $columns)
        <div id='box-actionmenu' class='box box-primary'>
            <div class='box-body'>
                @include("crudbooster::default.actionmenu")
            </div>
        </div>
    @endif

    @if(Request::get('file') && Request::get('import'))
        <ul class='nav nav-tabs'>
            <li style="background:#eeeeee">
                <a style="color:#111" onclick="if(confirm('Are you sure want to leave ?')) location.href='{{ CRUDBooster::mainpath('import-data') }}'" href='javascript:;'>
                    <i class='fas fa-upload'></i> Unggah File &raquo;
                </a>
            </li>
            <li style="background:#eeeeee">
                <a style="color:#111" href='#'>
                    <i class='fas fa-cogs'></i> Pengaturan &raquo;
                </a>
            </li>
            <li style="background:#ffffff" class='active'>
                <a style="color:#111" href='#'>
                    <i class='fas fa-cloud-upload-alt'></i> Importing &raquo;
                </a>
            </li>
        </ul>

        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Importing</h3>
                <div class="box-tools">
                </div>
            </div>

            <div class="box-body">
                <div id="no-error">
                    <p style='font-weight: bold' id='status-import'><i class='fas fa-spin fa-spinner'></i> Mohon menunggu...</p>
                    <div class="progress">
                        <div id='progress-import' class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40"
                            aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only">40% Complete (success)</span>
                        </div>
                    </div>
                </div>
                <div id="has-error" style="display: none;">
                    <p class="text-danger" style='font-weight: bold' id='status-import'><i class='fas fa-exclamation-triangle'></i> Ditemukan beberapa kesalahan...</p>
                    <div id="validation-msg" class="text-danger" ></div>
                    <a class="btn btn-success mt-3" href="{{ CRUDBooster::mainPath('import-data') }}">Pilih File Kembali</a>
                </div>
                @push('bottom')
                    <script type="text/javascript">
                        $(function () {
                            var total = {{ intval(Session::get('total_data_import')) }};
                            console.log('Total : ', total);
                            var int_prog = setInterval(function () {
                                $.post("{{ CRUDBooster::mainpath('do-import-chunk?file='.Request::get('file')) }}", {
                                    resume: 1
                                }, function (resp) {
                                    console.log('Progress : ', resp.progress);
                                    $('#progress-import').css('width', resp.progress + '%');
                                    $('#status-import').html("<i class='fa fa-spin fa-spinner'></i> Please wait importing... (" + resp.progress + "%)");
                                    $('#progress-import').attr('aria-valuenow', resp.progress);
                                    if (resp.progress >= 100) {
                                        $('#status-import').addClass('text-success').html("<i class='fa fa-check-square-o'></i> Import Data Completed !");
                                        clearInterval(int_prog);
                                    }
                                })
                            }, 2500);

                            $.post("{{ CRUDBooster::mainpath('do-import-chunk').'?file='.Request::get('file') }}", function (resp) {
                                console.log('Progress : ', JSON.stringify(resp));

                                if (resp.status == true) {
                                    $('#progress-import').css('width', '100%');
                                    $('#progress-import').attr('aria-valuenow', 100);
                                    $('#status-import').addClass('text-success').html("<i class='fa fa-check-square-o'></i> Import Data Completed !");
                                    clearInterval(int_prog);
                                    $('#upload-footer').show();
                                    console.log('Import Success');
                                }

                                else {
                                    clearInterval(int_prog);
                                    $('#no-error').remove();
                                    $('#validation-msg').html((resp.message));
                                    $('#has-error').show();
                                    new swal("Oops...", "Ditemukan beberapa kesalahan, mohon periksa file kembali...", "error");
                                    return false;
                                }
                            })
                        })
                    </script>
                @endpush
            </div>

            <div class="box-footer" id='upload-footer' style="display:none">
                <div class='pull-right'>
                    <a href='{{ CRUDBooster::mainpath("import-data") }}' class='btn btn-default'>
                        <i class='fa fa-upload'></i> Unggah File Lain
                    </a>
                    <a href='{{CRUDBooster::mainpath()}}' class='btn btn-success'>Selesai</a>
                </div>
            </div>
        </div>
    @endif

    @if(Request::get('file') && !Request::get('import'))
        <ul class='nav nav-tabs'>
            <li style="background:#eeeeee">
                <a style="color:#111" onclick="if(confirm('Are you sure want to leave ?')) location.href='{{ CRUDBooster::mainpath('import-data') }}'" href='javascript:;'>
                    <i class='fa fa-download'></i> Unggah File &raquo;
                </a>
            </li>
            <li style="background:#ffffff" class='active'>
                <a style="color:#111" href='#'>
                    <i class='fas fa-cogs'></i> Pengaturan &raquo;
                </a>
            </li>
            <li style="background:#eeeeee">
                <a style="color:#111" href='#'>
                    <i class='fas fa-cloud-upload-alt'></i> Importing &raquo;
                </a>
            </li>
        </ul>

        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengaturan</h3>
                <div class="box-tools">
                </div>
            </div>

            @php
                if ($data_sub_module) {
                    $action_path = Route($data_sub_module->controller."GetIndex");
                } else {
                    $action_path = CRUDBooster::mainpath();
                }

                $action = $action_path."/done-import?file=".Request::get('file').'&import=1';
            @endphp

            <form method='post' id="form" enctype="multipart/form-data" action='{{$action}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box-body table-responsive no-padding">
                    <div class='callout callout-info'>
                        * Just ignoring the column where you are not sure the data is suit with the column or not.<br/>
                        * Warning !, Unfortunately at this time, the system can't import column that contains image or photo url.
                    </div>
                    @push('head')
                        <style type="text/css">
                            th, td {
                                white-space: nowrap;
                            }
                        </style>
                    @endpush

                    <table class='table table-bordered' style="width:130%">
                        <thead>
                        <tr class='bg-blue'>
                            @foreach($table_columns as $k=>$column)
                                @php
                                $help = '';
                                if ($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'deleted_at') continue;
                                if (substr($column, 0, 3) == 'id_') {
                                    $relational_table = substr($column, 3);
                                    $help = "<a href='#' title='This is foreign key, so the System will be inserting new data to table `$relational_table` if doesn`t exists'><strong>(?)</strong></a>";
                                }
                                @endphp
                                <td data-no-column='{{$k}}'>{{ $column }} {!! $help !!}</td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            @foreach($table_columns as $k=>$column)
                                @php
                                    if ($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'deleted_at') continue;
                                @endphp
                                <td data-no-column='{{$k}}'>
                                    <select style='width:120px' class='form-control select_column' name='select_column[{{$k}}]'>
                                        <option value=''>** Kolom untuk : {{$column}}</option>
                                        @foreach($data_import_column as $z => $import_column)
                                            <option value='{{$import_column}}' {{ $column == $import_column ? 'selected' : '' }}>{{$import_column}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>

                @push('bottom')
                    <script type="text/javascript">
                        $(function () {
                            var total_selected_column = 0;
                            setInterval(function () {
                                total_selected_column = 0;
                                $('.select_column').each(function () {
                                    var n = $(this).val();
                                    if (n) total_selected_column = total_selected_column + 1;
                                })
                            }, 200);
                        })

                        function check_selected_column() {
                            var total_selected_column = 0;
                            $('.select_column').each(function () {
                                var n = $(this).val();
                                if (n) total_selected_column = total_selected_column + 1;
                            })
                            if (total_selected_column == 0) {
                                swal("Oops...", "Please at least 1 column that should adjusted...", "error");
                                return false;
                            } else {
                                return true;
                            }
                        }
                    </script>
                @endpush

                <div class="box-footer">
                    <div class='pull-right'>
                        <a onclick="if(confirm('Are you sure want to leave ?')) location.href='{{ CRUDBooster::mainpath("import-data") }}'" href='javascript:;'
                           class='btn btn-default'>Cancel</a>
                        <input type='submit' class='btn btn-primary' name='submit' onclick='return check_selected_column()' value='Import Data'/>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(!Request::get('file'))
        <ul class='nav nav-tabs'>
            <li style="background:#ffffff" class='active'>
                <a style="color:#111" onclick="if(confirm('Are you sure want to leave ?')) location.href='{{ CRUDBooster::mainpath('import-data') }}'" href='javascript:;'>
                    <i class='fas fa-download'></i> Unggah File &raquo;
                </a>
            </li>
            <li style="background:#eeeeee">
                <a style="color:#111" href='#'>
                    <i class='fas fa-cogs'></i> Pengaturan &raquo;
                </a>
            </li>
            <li style="background:#eeeeee">
                <a style="color:#111" href='#'>
                    <i class='fas fa-cloud-upload-alt'></i> Importing &raquo;
                </a>
            </li>
        </ul>

        <div id='box_main' class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Upload a File</h3>
                <div class="box-tools">

                </div>
            </div>

            @php
            if ($data_sub_module) {
                $action_path = Route($data_sub_module->controller."GetIndex");
            } else {
                $action_path = CRUDBooster::mainpath();
            }

            $action = $action_path."/do-upload-import-data";
            @endphp

            <form method='post' id="form" enctype="multipart/form-data" action='{{$action}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box-body">
                    <div class='callout callout-danger'>
                        <h4>Data Importer Tool</h4>
                        Mohon baca instruksi dibawah ini dengan seksama : <br/>
                        * Format file wajib : CSV<br/>
                        * Jika file terlalu besar, tidak dijamin seluruhnya akan berhasil terproses<br/>
                        * Mohon samakan dengan nama kolom pada file csv dengan nama kolom pada tabel database<br/>
                        * Struktur file : Baris 1 adalah nama kolom, dan baris selanjutnya adalah data
                    </div>

                    @if($guidances)
                        <h4>Panduan Serta Daftar Nama Kolom Pada CSV</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                    <tr class="bg-blue">
                                        <td>
                                            #
                                        </td>
                                        <td class="text-center">
                                            Nama Kolom
                                        </td>
                                        <td class="text-center">
                                            Tipe Data
                                        </td>
                                        <td class="text-center">
                                            Pilihan Isian
                                        </td>
                                        <td class="text-center">
                                            Validasi
                                        </td>
                                        <td class="text-center">
                                            Keterangan Tambahan
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 1
                                    @endphp
                                    @foreach($table_columns as $col)
                                        @if(!in_array($col, ['id', 'created_at', 'updated_at']) && $guidances[$col]['type'])
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $col }}</td>
                                                <td>{{ $guidances[$col]['type'] }}</td>
                                                <td width="10%" class="text-center">
                                                    @if(isset($guidances[$col]['dataenum']))
                                                        @php
                                                            $separate = explode(";", $guidances[$col]['dataenum']);
                                                        @endphp

                                                        @if(is_array($separate))
                                                            @foreach($separate as $sept)
                                                                <div class="badge bg-green">{{ $sept }}</div>
                                                            @endforeach
                                                        @endif
                                                    @elseif(isset($guidances[$col]['datatable']))
                                                        @php
                                                         $tbl = explode(",", $guidances[$col]['datatable']);
                                                         @endphp
                                                         @if(isset($tbl[0]) && Schema::hasTable($tbl[0]))
                                                         <a href="{{ CRUDBooster::mainPath('master-data?table=' . $tbl[0]) }}">Klik Disini<br>Untuk Mendapatkan List ID</a>
                                                         @endif
                                                    @endif
                                                </td>
                                                <td width="20%">
                                                    @php
                                                    $string = "";
                                                    $expld = explode("|", $guidances[$col]['validation']);
                                                    if(is_array($expld)){
                                                        foreach($expld as $key => $ex) {
                                                            switch($ex) {
                                                                case 'required' :
                                                                    $string .= "- <span class='text-danger'><b>Wajib Diisi</b></span><br>";
                                                                break;
                                                                case 'string' :
                                                                    $string .= "- Huruf/Angka<br>";
                                                                break;
                                                                case 'numeric' :
                                                                    $string .= "- Angka<br>";
                                                                break;
                                                                case 'integer' :
                                                                    $string .= "- Angka<br>";
                                                                break;
                                                                case 'validateLat' :
                                                                    $string .= "- Format latitude<br>";
                                                                break;
                                                                case 'validateLng' :
                                                                    $string .= "- Format longitude<br>";
                                                                break;
                                                                case 'nullable' :
                                                                    $string .= "- <span class='text-success'><b>Tidak Wajib Diisi</b></span><br>";
                                                                break;
                                                                case 'ipv4' :
                                                                    $string .= "- Format penulisan IP v4. <b>Misal : 192.168.0.0</b><br>";
                                                                break;
                                                                case strpos($ex, ':') !== false :
                                                                    $sep = explode(":", $ex);
                                                                    switch($sep[0]) {
                                                                        case 'regex' :
                                                                            if(strpos($sep[1], "08") !== false)
                                                                                $string .= "- Format penulisan HP. <b>Misal : 0816708705</b><br>";
                                                                            else
                                                                                break;
                                                                        break;
                                                                        case 'unique' : $string .= "- Tidak boleh ada yang sama/duplikat antar baris data<br>"; break;
                                                                        case 'min' : $string .= "- Minimal : " . number_format($sep[1]) . " Karakter<br>"; break;
                                                                        case 'max' : $string .= "- Maksimal : " . number_format($sep[1]) . " Karakter<br>"; break;
                                                                        case 'date_format' : $string .= "- Format Penulisan : " . $sep[1] . "<br>"; break;
                                                                        case 'exists' :
                                                                            $valid = explode(",", $sep[1]);
                                                                            $string .= "- Isian wajib sama dengan data di kolom <b>" . $valid[1] . "</b> pada tabel <b>" . $valid[0] . "</b>.<br>Silahkan unduh data pada tautan di kolom sebelah<br>";
                                                                        break;
                                                                        case 'in' :
                                                                        break;
                                                                        default: $string .= "- " . $ex . "<br>";
                                                                    }
                                                                break;
                                                                default: $string .= "- " . $ex . "<br>";
                                                            }
                                                        }
                                                    }
                                                    @endphp
                                                    {!! $string !!}
                                                </td>
                                                <td>{{ $guidances[$col]['placeholder'] ?? '-' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>
                    @endif

                    <div class="form-group">
                        <a class="btn btn-success" href="{{ CRUDBooster::mainpath("download-raw-csv?t=".time()) }}">
                            <i class="fas fa-download"></i> Unduh Contoh CSV
                        </a>
                    </div>

                    <div class='form-group'>
                        <label>Pilih File CSV</label>
                        <input type='file' name='userfile' class='form-control' required  accept=".csv" />
                        <div class='help-block'>Hanya menerima format: CSV</div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class='pull-right'>
                        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Batal</a>
                        <input type='submit' class='btn btn-primary' name='submit' value='Proses'/>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
