
    <div class="alert alert-danger text-center" style="border-radius: 30px" role="alert"><i class="zmdi zmdi-alarm"></i>@lang('messages.chromosome_exist')</div>

    <div class="text-center">
        <button class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px">
            <a href="{{ action('General_TimeTable\GenerateTimeTableController@create','') }}"
               class=""><i
                        class="zmdi zmdi-forward"></i>@lang('messages.refresh')</a>
        </button>
    </div>
