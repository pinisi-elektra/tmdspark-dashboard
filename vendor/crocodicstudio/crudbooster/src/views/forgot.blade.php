<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Pinisi Elektra - #KreasiAnakNegeri</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="robots" content="noindex,nofollow"/>
    <link rel="shortcut icon" href="{{ CRUDBooster::getSetting("favicon")?asset(CRUDBooster::getSetting("favicon")):asset("vendor/crudbooster/assets/logo_crudbooster.png") }}">
    <style type="text/css">
        /* roboto-regular - latin */
        @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        src: url('{{  url('assets/fonts/roboto-v20-latin-regular.eot') }}'); /* IE9 Compat Modes */
        src: local(''),
            url('{{  url('assets/fonts/roboto-v20-latin-regular.eot?#iefix') }}') format('embedded-opentype'), /* IE6-IE8 */
            url('{{  url('assets/fonts/roboto-v20-latin-regular.woff2') }}') format('woff2'), /* Super Modern Browsers */
            url('{{  url('assets/fonts/roboto-v20-latin-regular.woff') }}') format('woff'), /* Modern Browsers */
            url('{{  url('assets/fonts/roboto-v20-latin-regular.ttf') }}') format('truetype'), /* Safari, Android, iOS */
            url('{{  url('assets/fonts/roboto-v20-latin-regular.svg#Roboto') }}') format('svg'); /* Legacy iOS */
        }

        html, body {
            font-family: 'Roboto', serif !important;
        }
    </style>
    <link href="{{asset("vendor/crudbooster/assets/css/main.css")}}" rel="stylesheet" />
    <link href="{{asset("vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/font-awesome/css") }}/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset("vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.css')}}" rel="stylesheet" type="text/css" >
    <link href="{{ asset("vendor/crudbooster/assets/css/animate.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/css/custom.css")}}" rel="stylesheet" />
    <style type="text/css">
        *:focus, *:active {
            outline: none !important;
        }

        #login_wrapper {
            background: #fff;
            background-image: url('{{ asset('assets/images/footer-bg.svg') }}');
            background-position: bottom;
            background-repeat: no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        #login_wrapper .login-logo {
            position: absolute;
            top: 3vh;
            left: 3vh;
            max-width: 200px;
        }

        .container {
            display: flex;
            height: 100%;
        }

        .avatar-img {
            width: 250px;
            height: 350px;
            position: absolute;
            z-index: 2;
        }

        .avatar-left {
            transform: translate(-240px, -80px);
        }

        #logo {
            width: 100%;
        }

        .login-box, .register-box {
            margin: auto;
            display: flex;
            height: 100%;
        }

        .login-box-body {
            margin: auto;
            padding: 5% 8%;
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .2);
            background: rgba(255, 255, 255, 0.9);
            width: 330px;
            color: {{ CRUDBooster::getSetting("login_font_color")?:"#666666" }}  !important;
        }

        .lazy-img { display:none }

        .btn-submit {
            background: linear-gradient(19.3deg, #337ab7 -10.18%, #296091 114.01%);
            letter-spacing: .3rem;
            font-size: 15px;
            font-weight: bold;
            border-radius: 25px;
            text-transform: uppercase;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .form-control {
            height: 50px;
            background: #dde8ee;
            border-radius: 10px;
            font-size: 1.5rem;
        }

        .has-feedback > span{
            line-height: 50px;
            color: #337ab7 !important;
            font-size: 18px;
            margin-right: 3px;
        }

        .avatar-container {
            height: 100%;
            width: 100%;
            position: absolute;
            left: 0;
        }
    </style>
</head>

<body class="login-page" id="login_wrapper">
    <div class="container">
        <div class="login-box">
            <div class="login-box-body animated slideInUp">
                <a href="{{url("/")}}">
                    <img id="logo" class="lazy-img" title="{!! (CRUDBooster::getSetting("appname") == "CRUDBooster") ? "CRUDBooster" : CRUDBooster::getSetting("appname") !!}" data-src="{{ CRUDBooster::getSetting("logo") ? asset(CRUDBooster::getSetting("logo")) : asset("vendor/crudbooster/assets/logo_crudbooster.png") }}" />
                </a>

                <h4 class="login-box-msg m-0">Lupa Kata Sandi</h4>

                <form action="{{ route('postForgot') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" name='email' required placeholder="Email Address"/>
                        <span class="fas fa-envelope form-control-feedback"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <div class="m-0">
                            {{cbLang("forgot_text_try_again")}}<br><a href='{{route("getLogin")}}'>{{cbLang("click_here")}}</a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block btn-submit">
                                <i class="fa fa-envelope mr-3"></i>
                                {{cbLang("button_submit")}}
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <p class="my-3 mt-3 text-muted">PT. Pinisi Elektra</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script src="{{asset("vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js")}}"></script>
<script src="{{asset("vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/js/jquery.imageloader.js")}}" type="text/javascript"></script>
<script src="{{asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.min.js')}}"></script>

<script>
    $(function() {
        $('.lazy-img').imageloader({
            callback: function (elm) {
                $(elm).fadeIn(1000);
            },
        });
    });
</script>
@include('sweetalert::alert')
</html>
