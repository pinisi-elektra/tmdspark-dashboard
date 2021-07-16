<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>

<!-- Bootstrap 3.4.1 JS -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/dist/js/app.js') }}" type="text/javascript"></script>

<!--BOOTSTRAP DATEPICKER-->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/datepicker3.css') }}">

<!--SlimScroll-->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>

<!--BOOTSTRAP DATERANGEPICKER 2.1.27 AND MOMENT 2.13.0 -->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/daterangepicker-bs3.css') }}">

<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

<link rel='stylesheet' href='{{ asset("vendor/crudbooster/assets/lightbox/dist/css/lightbox.min.css") }}'/>
<script src="{{ asset('vendor/crudbooster/assets/lightbox/dist/js/lightbox.min.js') }}"></script>

<!--SWEET ALERT-->
<script src="{{asset('vendor/sweetalert/sweetalert.all.js')}}"></script>
{{-- <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.css')}}"> --}}

<!--MONEY FORMAT-->
<script src="{{asset('vendor/crudbooster/jquery.price_format.2.0.min.js')}}"></script>

<!--DATATABLE-->
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.css')}}">
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset("assets/js/jquery.imageloader.js")}}" type="text/javascript"></script>

<!--PACE-->
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/pace/pace.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/pace/pace.min.css')}}">

<!--SELECT2-->
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/select2/select2-bootstrap.min.css')}}">
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/select2/select2.full.min.js')}}"></script>

<!--ANIMATE-->
<link href="{{ asset("vendor/crudbooster/assets/css/animate.css") }}" rel="stylesheet" type="text/css"/>

<!--CHARTJS-->
<link rel="stylesheet" href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/chartjs/Chart.min.css')}}">
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/chartjs/Chart.min.js')}}"></script>

<!--ALPINE-->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>

<!--COUNTUP-->
<script src="{{asset("assets/js/waypoints.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/countup.full.min.js")}}" type="text/javascript"></script>

<script>
    var ASSET_URL = "{{asset('/')}}";
    var APP_NAME = "{{CRUDBooster::getSetting('appname')}}";
    var ADMIN_PATH = '{{url(config("crudbooster.ADMIN_PATH")) }}';
    var NOTIFICATION_JSON = "{{route('NotificationsControllerGetLatestJson')}}";
    var NOTIFICATION_INDEX = "{{route('NotificationsControllerGetIndex')}}";

    var NOTIFICATION_YOU_HAVE = "{{cbLang('notification_you_have')}}";
    var NOTIFICATION_NOTIFICATIONS = "{{cbLang('notification_notification')}}";
    var NOTIFICATION_NEW = "{{cbLang('notification_new')}}";

    $(function () {
        $('.datatables-simple').DataTable();

        $('.lazy-img').imageloader({
            callback: function (elm) {
                $(elm).fadeIn();
            },
        });

        $.fn.select2.defaults.set( "theme", "bootstrap" );

        $(window).on('resize', function() {
            resizeS2();
        });

        $('.sidebar-toggle').click(function(){
            resizeS2();
        });

        function resizeS2() {
            $('.select2-container').each(function(){
                setTimeout(() => {
                    $(this).width($(this).parent().width());
                }, 250);
            });
        }
    })

    $(document).ajaxStart(function () {
        Pace.start()
        Pace.options = {
            ajax : {
                trackMethods: ['GET'],
                ignoreURLs: ['{{route('NotificationsControllerGetLatestJson')}}']
            },
        };
    });
</script>
<script src="{{asset('vendor/crudbooster/assets/js/main.js').'?r='.time()}}"></script>

@include('sweetalert::alert')

@livewireScripts
