<header class="main-header">
    <a href="{{url(config('crudbooster.ADMIN_PATH'))}}" title='{{CRUDBooster::getSetting('appname')}}' class="logo">
        {{CRUDBooster::getSetting('appname')}}
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fas fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title='Notifications' aria-expanded="false">
                        <i id='icon_notification' class="fa fa-bell"></i>
                        <span id='notification_count' class="label label-danger" style="display:none">0</span>
                    </a>
                    <ul id='list_notifications' class="dropdown-menu animated fadeIn">
                        <li class="header text-center">
                            <b>{{cbLang("text_no_notification")}}</b>
                        </li>
                        <li>
                            <div class="slimScrollDiv" style="position: relative; overflow-y: auto; width: auto; height: auto;">
                                <ul class="menu" style="overflow: hidden; width: 100%; height: unset;">
                                    <li>
                                        <a href="#">
                                            <em>{{cbLang("text_no_notification")}}</em>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="footer">
                            <a href="{{route('NotificationsControllerGetIndex')}}">
                                {{cbLang("text_view_all_notification")}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img data-src="{{ Avatar::create(CRUDBooster::myName())->toBase64() }}" class="user-image lazy-img" alt="User Image"/>
                        <span class="hidden-xs">{{ CRUDBooster::myName() }}</span>
                    </a>
                    <ul class="dropdown-menu animated fadeIn">
                        <li class="user-header">
                            <img data-src="{{ Avatar::create(CRUDBooster::myName())->toBase64() }}" class="img-circle lazy-img" alt="User Image"/>
                            <p>
                                {{ CRUDBooster::myName() }}
                                <small>{{ CRUDBooster::myPrivilegeName() }}</small>
                                <small><em><?php echo date('d F Y')?></em></small>
                            </p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-{{ cbLang('left') }}">
                                <a href="{{ route('AdminCmsUsersControllerGetProfile') }}" class="btn btn-default btn-flat">
                                    <i class='fa fa-user'></i> {{cbLang("label_button_profile")}}
                                </a>
                            </div>
                            <div class="pull-{{ cbLang('right') }}">
                                <a href="javascript:void(0)" onclick="new swal({
                                        title: '{{cbLang('alert_want_to_logout')}}',
                                        icon:'info',
                                        showCancelButton:true,
                                        allowOutsideClick:true,
                                        confirmButtonColor: '#DD6B55',
                                        confirmButtonText: '{{cbLang('button_logout')}}',
                                        cancelButtonText: '{{cbLang('button_cancel')}}',
                                        closeOnConfirm: false
                                        })
                                        .then(function(result) {
                                            if (result.isConfirmed) {
                                                location.href = '{{ route("getLogout") }}';
                                            }
                                        });" title="{{cbLang('button_logout')}}" class="btn btn-danger btn-flat" style="background: #d73925"><i class='fa fa-power-off'></i></a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
