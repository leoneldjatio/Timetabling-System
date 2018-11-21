@extends('layouts.app')

@section('content')
        <div class="container right">
        <div class="row center">
            <div class="col m4" >
                <img src="{{ asset('avatars/lecturer.png') }}" class="img-responsive">
            </div>
        <div class="col m6" id="lect">
            <div class="panel panel-default">
                <div  class="{{ trans('messages.theme') . '  center z-depth-2' }}">
                    <div class=" {{ trans('messages.alert_theme') . ' card-content center'}}">
                        <br>
                        <h6>REGISTER USER! </h6>
                        <br>
                    </div>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="input-field {{ $errors->has('name') ? ' has-error' : '' }}">
                            <i class="{!! trans('messages.theme') .'-text material-icons prefix' !!}">person_pin</i>
                            <input type="text" name="name" id="name" placeholder="{{ trans('messages.name') }}"
                                   value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                         </span>
                            @endif
                            <label for="email" class="{{ trans('messages.theme') . '-text' }}"><strong>@lang('messages.name')</strong></label>
                        </div>

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
                        <div class="input-field {{ $errors->has('password_placeholder') ? ' has-error' : '' }}">
                            <i class="{{ trans('messages.theme') .'-text material-icons prefix' }}">lock</i>
                            <input type="password" id="password-confirm" name="password_confirmation"
                                   placeholder="{{ trans('messages.password') }}" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                         </span>
                            @endif
                            <label for="password-confirm" class="{{ trans('messages.theme') . '-text' }}">@lang('messages.cpassword')</label>
                        </div>

                        <button type="submit"
                                class="{{ trans('messages.text') . ' waves-effect waves btn fullwidth  ' . trans('messages.theme') . ' center col s12' }}">
                            <i class="{{ trans('messages.text') . ' material-icons left'}}">directions_run</i><strong>Register</strong>
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
