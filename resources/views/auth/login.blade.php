<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Admin</title>

    <!-- Vendor CSS -->
    <link href="asset {{('vendors/bower_components/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="asset {{('vendors/bower_components/google-material-color/dist/palette.css')}}" rel="stylesheet">
    <link href="asset {{('vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet')}}">

    <!-- CSS -->
    <link href="{{('css/app.min.1.css')}}" rel="stylesheet">
    <link href="{{('css/app.min.2.css')}}" rel="stylesheet">

</head>
<body>
<div class="container center" id="login">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="{{ 'panel-heading ' . trans('messages.theme') . ' z-depth-5' }} ">
                    <div>

                        <h4 style="text-align: center"
                            class="{{ trans('messages.text')  }}">{!!trans('messages.go_caption')!!}</h4>
                    </div>
                    <div class="panel-heading center"> <a href="#" class="brand-logo center"> <img src="{{ asset('avatars/icon.png') }}" class="center"></a></div>

                    <span style="text-align:center;"
                          class="{{ trans('messages.text')  }}">{!!trans('messages.login_header')!!}</span><br>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="input-field {{ $errors->has('email') ? ' has-error' : '' }}">
                            <i class="{!! trans('messages.theme') .'-text material-icons prefix' !!}">email</i>
                            <input type="email" name="email" id="email" placeholder="{{ trans('messages.email_placeholder') }}"
                                   value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                         </span>
                            @endif
                            <label for="email" class="{{ trans('messages.theme') . '-text' }}"><strong>@lang('messages.email')</strong></label>
                        </div>
                        <div class="input-field {{ $errors->has('password_placeholder') ? ' has-error' : '' }}">
                            <i class="{{ trans('messages.theme') .'-text material-icons prefix' }}">lock</i>
                            <input type="password" id="password" name="password"
                                   placeholder="{{ trans('messages.password') }}" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                         </span>
                            @endif
                            <label for="password" class="{{ trans('messages.theme') . '-text' }}">@lang('messages.password')</label>
                        </div>

                        <div class="input field left">
                            <input type="checkbox" id="remember" class="checkbox-color filled-in">
                            <label for="remember" >@lang('messages.remember_me')</label>
                        </div>
                        <br><br>

                        <button type="submit"
                                class="{{ trans('messages.text') . ' waves-effect waves btn fullwidth ' . trans('messages.theme') . ' center col s12' }}">
                            <i class="{{ trans('messages.text') . ' material-icons left'}}">directions_run</i><strong>Login</strong>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
