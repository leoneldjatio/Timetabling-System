@extends('layouts.app')
@inject('role','App\Role')
@section('content')
    <div class="card">
    <div class="container">
            <div class="{{ trans('messages.theme') . '  center' }}">
                <div class=" {{ trans('messages.alert_theme') . ' card-content center'}}">
                    HI {{ strtoupper(Auth::user()->user_name) }} ,Welcome to your Timetabling System Account!
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-9 grey lighten-5 left"
                     style="border-right-style: solid;border-right-color: lightgrey;border-right-width: 1px;">
                    <img src="{{ asset('avatars/profile.png') }}" class="responsive-img">
                    <div id="card_alert" class="{{ trans('messages.theme') . '  center' }}">
                        <div class=" {{ trans('messages.alert_theme') . ' card-content center'}}">
                            HI {{ strtoupper(Auth::user()->user_name) }} !
                        </div>
                    </div>
                    <br>
                    <div>
                        <h6><b>Contact</b></h6>
                        <p><i class="zmdi zmdi-email"></i>{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="col-sm-3 right">
                    <div class="container">
                        <h6><i class="zmdi zmdi-chart"></i><b>Summary</b></h6>
                        <div style="display: block;" class="container">
                            <p>Account Status: {{ strtoupper($role::find(Auth::user()->roles_role_id)->role_name) }}</p>
                            <p>Academic Year: {{ date('Y') . '/' . (date('Y') + 1) }}</p>
                        </div>
                        <br>
                        <h6><i class="zmdi zmdi-card"></i><b>Basic Information</b></h6>
                        <div style="display: block;" class="container">
                            <p>Name: {{ Auth::user()->user_name }}</p>
                            <p>Email: {{Auth::user()->email }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
