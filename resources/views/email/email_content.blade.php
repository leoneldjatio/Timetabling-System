<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>

    <title>@lang('messages.go-student_mail')</title>

    <!-- Material Design fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{!! asset('css/materialize.css') !!}" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="{!! asset('css/style.css') !!}" type="text/css" rel="stylesheet" media="screen,projection"/>
    <script type="text/javascript" src="{!! asset('/jquery/jquery-2.1.1.min.js') !!}"></script>
    <script src="{!! asset('js/materialize.js') !!}"></script>
    <script src="{!! asset('js/init.js') !!}"></script>
</head>
<body>
<h2>@lang('messages.university')!</h2>
<div>
    <h3>@lang('messages.go_student_mail')</h3>
    <h1>From: {{ $mailable['from'] }}</h1>
    <h1>Title: {{ $mailable['title'] }}</h1>
    <h4>Content<br>{{ $mailable['content'] }}</h4>;
</div>
</body>

</html>