<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"  class="fontawesome-i2svg-pending">
<head>
    <title>{{ ($page_title) ? get_setting("appname") . " : " . strip_tags($page_title) : "Admin Area" }}</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="robots" content="noindex,nofollow"/>
    <style type="text/css">
        @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        src: url('{{  url('assets/fonts/roboto-v20-latin-regular.eot') }}');
        src: local(''),
            url('{{  url('assets/fonts/roboto-v20-latin-regular.eot?#iefix') }}') format('embedded-opentype'),
            url('{{  url('assets/fonts/roboto-v20-latin-regular.woff2') }}') format('woff2'),
            url('{{  url('assets/fonts/roboto-v20-latin-regular.woff') }}') format('woff'),
            url('{{  url('assets/fonts/roboto-v20-latin-regular.ttf') }}') format('truetype'),
            url('{{  url('assets/fonts/roboto-v20-latin-regular.svg#Roboto') }}') format('svg');
        }
    </style>
    <link rel="shortcut icon" href="{{ CRUDBooster::getSetting("favicon")?asset(CRUDBooster::getSetting("favicon")):asset("vendor/crudbooster/assets/logo_crudbooster.png") }}">
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/font-awesome/css") }}/font-awesome.min.css" rel="stylesheet" type="text/css"defer />
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/skins/_all-skins.min.css") }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset("vendor/crudbooster/assets/css/main.css") }}"/>
    <link rel="stylesheet" href="{{ asset("assets/css/custom.css?v=") . time() }}"/>

    @if($style_css)
        <style type="text/css">
            {!! $style_css !!}
        </style>
    @endif

    @if($load_css)
        @foreach($load_css as $css)
            <link href="{{$css}}" rel="stylesheet" type="text/css"/>
        @endforeach
    @endif

    <style type="text/css">
        *:not(.fa,.fas) {
            font-family: 'Roboto', serif !important;
        }

        *:focus {
            outline: none !important;
        }

        .dropdown-menu-action {
            left: -130%;
        }

        .btn-group-action .btn-action {
            cursor: default
        }

        #box-header-module {
            box-shadow: 10px 10px 10px #dddddd;
        }

        .sub-module-tab li {
            background: #F9F9F9;
            cursor: pointer;
        }

        .sub-module-tab li.active {
            background: #ffffff;
            box-shadow: 0px -5px 10px #cccccc
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
            border: none;
        }

        .nav-tabs > li > a {
            border: none;
        }

        .breadcrumb {
            margin: 0 0 0 0;
            padding: 0 0 0 0;
        }

        .form-group > label:first-child {
            display: block
        }

        #table_dashboard.table-bordered, #table_dashboard.table-bordered thead tr th, #table_dashboard.table-bordered tbody tr td {
            border: 1px solid #bbbbbb !important;
        }

        .main-header .sidebar-toggle:before {
            content: unset !important;
        }

        table>thead>tr>th, table>thead>tr>td, table>tbody>tr>td {
            white-space: nowrap;
        }

        table>thead>tr>th:not(:first-child) {
            /* min-width: 150px; */
        }

        ::-webkit-scrollbar {
            height: 7px;
            width: 7px;
        }

        ::-webkit-scrollbar-track, ::-webkit-scrollbar-thumb {
            border-radius: 0;
        }

        table.sticky-x tr td:last-child, table.sticky-x tr > th:last-child{
            position: sticky;
            width: fit-content;
            right: 0;
            z-index: 10;
            background-color: #fff;
        }

        table.sticky-x td:last-child, table.sticky-x th:last-child {
            border: 0;
            box-shadow: -2px 0px 3px 0px #f1f1f1;
        }
    </style>

    @stack("head")

    @livewireStyles
</head>

<body class="@php echo (Session::get("theme_color"))?:"skin-blue"; echo " "; echo config("crudbooster.ADMIN_LAYOUT"); @endphp {{ ($sidebar_mode)?:""}}">
    <div id="app" class="wrapper">
        @include("crudbooster::header")
        @include("crudbooster::sidebar")
        <div class="content-wrapper">
            <section class="content-header">
                @php
                $module = CRUDBooster::getCurrentModule();
                @endphp

                @if($module)
                    <h1>
                        <i class="{!! ($page_icon)?:$module->icon !!}"></i> {!! ucwords(($page_title)?:$module->name) !!} &nbsp;&nbsp;
                    </h1>

                    <ol class="breadcrumb mb-4">
                        <li>
                            <a href="{{CRUDBooster::adminPath() }}">
                                <i class="fa fa-dashboard"></i> {{ cbLang("home") }}
                            </a>
                        </li>
                        <li class="active">
                            {{$module->name}}
                        </li>
                    </ol>

                    <hr class="hzn-hr hidden-xs  {{ CRUDBooster::getCurrentMethod() == "getIndex" || !empty($index_button) ? 'my-4' : 'mt-3 mb-0' }}">

                    @if(CRUDBooster::getCurrentMethod() == "getIndex" || !empty($index_button))
                        <div style="display: inline-flex; flex-wrap: wrap; gap: 10px; width: 100%;justify-content: flex-end;">
                            @if(CRUDBooster::getCurrentMethod() == "getIndex")
                                @if($button_show)
                                    <a href="{{ CRUDBooster::mainpath() }}" id="btn_show_data" class="btn btn-primary btn-social" title="{{cbLang("action_show_data") }}">
                                        <i class="fa fa-table"></i> {{cbLang("action_show_data") }}
                                    </a>
                                @endif

                                @if($button_add && CRUDBooster::isCreate())
                                    <a href="{{ CRUDBooster::mainpath("add")."?return_url=".urlencode(Request::fullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field }}" id="btn_add_new_data" class="btn btn-success btn-social" title="{{cbLang("action_add_data") }}">
                                        <i class="fa fa-plus-circle"></i> {{cbLang("action_add_data") }}
                                    </a>
                                @endif
                            @endif

                            @if($button_export && CRUDBooster::getCurrentMethod() == "getIndex")
                                <a href="javascript:void(0)" id="btn_export_data" data-url-parameter="{{$build_query}}" title="Export Data" class="btn btn-primary btn-social btn-export-data">
                                    <i class="fa fa-upload"></i> {{cbLang("button_export") }}
                                </a>
                            @endif

                            @if($button_import && CRUDBooster::getCurrentMethod() == "getIndex")
                                <a href="{{ CRUDBooster::mainpath("import-data") }}" id="btn_import_data" data-url-parameter="{{$build_query}}" title="Import Data" class="btn btn-primary btn-social btn-import-data">
                                    <i class="fa fa-download"></i> {{cbLang("button_import") }}
                                </a>
                            @endif

                            @if(!empty($index_button))
                                @foreach($index_button as $ib)
                                    <a href="{{$ib["url"]}}" id="{{str_slug($ib["label"]) }}" class="btn {{ ($ib["color"])?"btn-".$ib["color"]:"btn-primary"}} btn-social"
                                        @if($ib["onClick"])
                                            onClick="return {{$ib["onClick"]}}"
                                        @endif
                                        @if($ib["onMouseOver"])
                                            onMouseOver="return {{$ib["onMouseOver"]}}"
                                        @endif
                                        @if($ib["onMouseOut"])
                                            onMouseOut="return {{$ib["onMouseOut"]}}"
                                        @endif
                                        @if($ib["onKeyDown"])
                                            onKeyDown="return {{$ib["onKeyDown"]}}"
                                        @endif
                                        @if($ib["onLoad"])
                                            onLoad="return {{$ib["onLoad"]}}"
                                        @endif
                                    >
                                        <i class="{{$ib["icon"]}}"></i> {{$ib["label"]}}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                @else
                    <h1>{{CRUDBooster::getSetting("appname") }}
                        <small> {{ cbLang("text_dashboard") }} </small>
                    </h1>
                @endif
            </section>

            <section id="content_section" class="content animated fadeIn">
                @if(@$alerts)
                    @foreach(@$alerts as $alert)
                        <div class="callout callout-{{$alert["type"]}}">
                            {!! $alert["message"] !!}
                        </div>
                    @endforeach
                @endif

                @if (Session::get("message")!="")
                    <div class="alert alert-{{ Session::get("message_type") }}">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-info"></i> {{ cbLang("alert_".Session::get("message_type")) }}</h4>
                        {!!Session::get("message")!!}
                    </div>
                @endif

                @yield("content")
            </section>
        </div>
        @include("crudbooster::footer")
    </div>

    @include("crudbooster::admin_template_plugins")

    @if($load_js)
        @foreach($load_js as $js)
            <script src="{{ $js }}"></script>
        @endforeach
    @endif

    <script type="text/javascript">
        var site_url = "{{ url('/') }}";
    </script>

    @if($script_js)
        <script type="text/javascript">
            {!! $script_js !!}
        </script>
    @endif

    @stack("bottom")
</body>
</html>
