<div class="alert alert-success alert-dismissible" role="alert" style="border-radius: 30px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    @lang('messages.configuration_success')
</div>
<br><br>
<div class="row" id="student_info">
    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.name'): {{ Auth::user()->user_name }}</p>
            <p>@lang('messages.email') : {{ Auth::user()->email}} </p>
        </div>
    </div>

    <div class="col-sm-4 m-b-25 "  >
        <div class="icon-block text-center">
            <br> <br>
            <img src="{{ asset('avatars/icon.png') }}" alt="university">
            <br>
        </div>
    </div>

    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.academic_year'): {{ date('Y') . '/'. (date('Y')+1) }}</p>
            <p>@lang('messages.configured_date'): {{ date('d/m/Y h:i:s a', time()) }}</p>
        </div>
    </div>
</div>
<div class="center">
    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
</div> <br>

<div class="row">
    <div class="col-sm5">
        <div class= "table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="@lang('messages.table_theme')"><b>@lang('messages.config')</b></th>
                <th class="@lang('messages.table_theme')"><b>@lang('messages.config_value')</b></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="@lang('messages.table_theme')"><b>@lang('messages.dperiods')</b></td>
                <td @lang('messages.table_theme')>{{ $config->max_day_period }}</td>
            </tr>
            <tr>
                <td class="@lang('messages.table_theme')"><b>@lang('messages.lperiods')</b></td>
                <td @lang('messages.table_theme')>{{ $config->lecturer_max_day_period }}</td>
            </tr>
            <tr>
                <td class="@lang('messages.table_theme')"><b>@lang('messages.pduration')</b></td>
                <td @lang('messages.table_theme')>{{ $config->period_duration }}</td>
            </tr>
            <tr>
                <td class="@lang('messages.table_theme')"><b>@lang('messages.start_time')</b></td>
                <td @lang('messages.table_theme')>{{ $config->start_time }}</td>
            </tr>

            <tr>
                <td class="@lang('messages.table_theme')"><b>@lang('messages.select_split')</b></td>
                <td @lang('messages.table_theme')>{{ $config->should_split }}</td>
            </tr>

            </tbody>
        </table>
        </div>
    <div class="col-sm4">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="@lang('messages.table_theme')"><b>@lang('messages.day_slot')</b></th>
            </tr>
            </thead>
            <tbody>
            @foreach($weekdays as $weekday)
                <tr>
                    <td @lang('messages.table_theme')>{{ $weekday }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    </div>
</div>
<br><br>

    <a href="{{ action('General_TimeTable\GenerateTimeTableController@create') }}" class="btn btn-float bgm-red m-btn"><i
                class="zmdi zmdi-open-in-new "></i></a>




