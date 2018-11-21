@inject('slots','App\TimeSlot')
<div class="alert alert-success alert-dismissible" role="alert" style="border-radius: 30px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{$department->department_name. '\'s ' }}@lang('messages.generate_department')
</div>
<br><br>
<div class="row" id="department_info">
    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.name'): {{ Auth::user()->user_name }}</p>
            <p>@lang('messages.email') : {{ Auth::user()->email}} </p>
            <p>@lang('messages.printed_date'): {{ date('d/m/Y h:i:s a', time()) }}</p>
        </div>
    </div>

    <div class="col-sm-4 m-b-25">
        <div class="icon-block text-center">
            <br> <br>
            <img src="{{ asset('avatars/icon.png') }}" alt="university">
            <br>
        </div>
    </div>

    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.department'): {{$department->department_name}}</p>
            <p>@lang('messages.semester') N<sup>o</sup>: {{ $semester->semester_name }} </p>
            <p>@lang('messages.academic_year'): {{ $year->year_value }}</p>
        </div>
    </div>
</div>
<div class="center">
    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
</div> <br>
<div class="table-responsive">
<table class="table table-striped" id="print">
    <thead>
    <tr>
        <th class="@lang('messages.table_theme')"><b>@lang('messages.table_side_caption')</b></th>
        @foreach($semester->weekDays()->get()->first()->timeSlots()->get() as $timeslot)
            <th class="@lang('messages.table_theme')"><b>{{ $timeslot->time_slot_name }}</b></th>
        @endforeach
    </tr>
    </thead>

    <tbody>

    @foreach($semester->weekDays()->get() as $week_day)
        <tr>
            <td class="@lang('messages.table_theme')"><b>{{ $week_day->week_day_name }}</b></td>
            @foreach($week_day->timeSlots()->get() as $time_slot)
                <td>
                    @foreach($time_slot->allocations()->get() as $allocation)

                        @foreach($department->courses()->get() as $course)
                            @if($allocation->course_code == $course->course_code)
                                {{  $allocation->course_code . ' ' .  $allocation->room_name  }}<br>{{ $allocation->teacher_name }}<br>
                            @endif
                        @endforeach

                    @endforeach
                </td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>
</div>
<br><br>
<div class="card-content">
    <button type="" class="btn btn-float bgm-white m-btn ">
        <a class="{{ trans('messages.theme')}}" href="{{ action('General_TimeTable\DepartmentTimeTableController@downloadPDF',["year" => $year->year_id,"semester" => $semester->semester_id,"departmentID" => $department->department_id]) }}">
            <i class="zmdi zmdi-download"></i></a></button
</div>

