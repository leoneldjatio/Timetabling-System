<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TimetablingSystem') }}</title>

    <link type="image/x-icon" href="{{ asset('avatars/icon.png') }}" rel="icon">

    <!-- Vendor CSS -->
    <link href="{{ asset('vendors/bower_components/animate.css/animate.min.css') }}" rel="stylesheet">

    <link href="{{ asset('vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bower_components/google-material-color/dist/palette.css') }}" rel="stylesheet">

    <link href="{{ asset('vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bower_components/nouislider/distribute/jquery.nouislider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/farbtastic/farbtastic.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bower_components/chosen/chosen.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/summernote/dist/summernote.css') }}" rel="stylesheet">
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>


    <!-- CSS -->
    <link href="{{ asset('css/app.min.1.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.2.css') }}" rel="stylesheet">
    <link href="{{ asset('sweetalert2.min.css') }}" rel="stylesheet">




    <link href="{{ asset('css/decorate.css') }}" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css')}}">

    <script src="{{ asset('js/jquery.spin.js') }}"></script>
    <link href="{{ asset('stylesheets/jquery.spin.css" rel="stylesheet" type="text/css')}}"/>


    <script src="{{ asset('js/vendor/modernizr-2.6.2.min.js') }}"></script>


</head>
<body data-ma-header="teal">
<header id="header" class="media">
    <div class="pull-left h-logo">
        <a href="#" class="hidden-xs">
            Go-Student
            <small>The University of Buea</small>
        </a>

        <div class="menu-collapse" data-ma-action="sidebar-open" data-ma-target="main-menu">
            <div class="mc-wrap">
                <div class="mcw-line top palette-White bg"></div>
                <div class="mcw-line center palette-White bg"></div>
                <div class="mcw-line bottom palette-White bg"></div>
            </div>
        </div>
    </div>

    <ul class="pull-right h-menu">

        <li class="dropdown hidden-xs hidden-sm h-apps">
            <a data-toggle="dropdown" href="">
                <i class="hm-icon zmdi zmdi-mall"></i>
            </a>
        </li>
        <li class="dropdown hidden-xs">
            <a href="">
                <button class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-help"></i></button>
            </a>
        </li>
        <li class="hm-alerts" data-user-alert="sua-messages" data-ma-action="sidebar-open"
            data-ma-target="user-alerts">
            <a href=""><i class="hm-icon zmdi zmdi-notifications"></i></a>
        </li>

        <li class="dropdown hm-profile">
            <a data-toggle="dropdown" href="">
                <img src="{{ asset('/avatars/profile.png') }}" alt="">
            </a>

            <ul class="dropdown-menu pull-right dm-icon">
                <li style="text-align: center;"><a href="#" class="" style="color: blue">B.Eng Computer Engineering</a>
                </li>
                <li class="divider"></li>
                <li><a href="#"><i class="zmdi zmdi-balance-wallet"></i>Pay Service Charges</a></li>
                <li><a href="#"><i class="zmdi zmdi-eye" ></i> Transaction Details</a></li>
                <li class="divider"></li>
                <li><a href="#"><i class="zmdi zmdi-edit"></i>Update Profile</a></li>
                <li><a href="#"><i class="zmdi zmdi-key"></i>Change Password</a></li>
                <li><a href="#"><i class="zmdi zmdi-help"></i>Help</a></li>
                <li class="divider"></li>
                <li><a href="{{ action('Auth\LoginController@logout','') }}"><i class="zmdi zmdi-power" style="color: red"> Logout </i></a></li>

           </ul>
        </li>
    </ul>

</header>
<section id="main">
@section('navbar')
    @include('layouts.navbar.navbar')
    @show
<section id="content">
    @yield('content')
</section>
@section('footer')
    @include('layouts.footer.footer')
    @show
</section>

<!-- Page Loader -->
<div class="page-loader palette-Teal bg">
    <div class="preloader pl-xl pls-white">
        <svg class="pl-circular" viewBox="25 25 50 50">
            <circle class="plc-path" cx="50" cy="50" r="20"/>
        </svg>
    </div>
</div>

<!-- Older IE warning message -->
<!--[if lt IE 9]>
<div class="ie-warning">
    <h1 class="c-white">Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="img/browsers/chrome.png" alt="">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="img/browsers/firefox.png" alt="">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="img/browsers/opera.png" alt="">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="img/browsers/safari.png" alt="">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="img/browsers/ie.png" alt="">
                    <div>IE (New)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->

<!-- Javascript Libraries -->

<script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="vendors/bower_components/Waves/dist/waves.min.js"></script>
<script src="vendors/bootstrap-growl/bootstrap-growl.min.js"></script>

<script src="vendors/bower_components/moment/min/moment.min.js"></script>
<script src="vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src="vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>
<script src="vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="vendors/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
<script src="vendors/summernote/dist/summernote-updated.min.js"></script>




<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
<![endif]-->

<script src="vendors/bower_components/chosen/chosen.jquery.min.js"></script>
<script src="vendors/fileinput/fileinput.min.js"></script>
<script src="vendors/input-mask/input-mask.min.js"></script>
<script src="vendors/farbtastic/farbtastic.min.js"></script>


<script src="js/functions.js"></script>
<script src="js/actions.js"></script>
<script src="js/demo.js"></script>
</body>
</html>