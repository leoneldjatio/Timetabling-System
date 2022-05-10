<div class="alert alert-success alert-dismissible" role="alert" style="border-radius: 30px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ Auth::user()->user_name . '\'s ' }}@lang('messages.sample_timetable')
</div>
<br><br>
<div class="row" id="teacher_info">
    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.name'): {{ Auth::user()->user_name }}</p>
            <p>@lang('messages.matricule') N<sup>o</sup>: {{ Auth::user()->email}} </p>
            <p>@lang('messages.major'): {{ Auth::user()->roles_role_id }}</p>
            <p>@lang('messages.minor'): {{ Auth::user()->roles_role_id }}</p>
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
            <p>@lang('messages.printed_date'): {{ date('d/m/Y h:i:s a', time()) }}</p>
        </div>
    </div>
</div>
<div class="text-center">
    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
</div> <br>
<div class="table-responsive">
<table class="table table-bordered" id="print">
    <thead>
    <tr>
        <th class="@lang('messages.table_theme2')"><b><a style="color: black">@lang('messages.table_side_caption')</a></b></th>
        @foreach($semester->weekDays()->get()->first()->timeSlots()->get() as $timeslot)
            <th class="@lang('messages.table_theme')"><b><a style="color: white">{{ $timeslot->time_slot_name }}</a></b></th>
        @endforeach
    </tr>
    </thead>

    <tbody>

    @foreach($semester->weekDays()->get() as $week_day)
        <tr>
            <th class="@lang('messages.table_theme')"><b><a style="color: white">{{ $week_day->week_day_name }}</a></b></td>
            @foreach($week_day->timeSlots()->get() as $time_slot)
                <td>
                    @foreach($time_slot->allocations()->get() as $allocation)
                        @foreach($compCourses as $course)
                            @if($allocation->course_code == $course->course_code)

                                <a style="color: brown">{{  $allocation->course_code . ' ' .  $allocation->room_name  }}</a>
                                <br><a style="color: black">{{ $allocation->teacher_name }}</a><br>

                                @break
                            @else

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
    <div class="card-content">
        <button type="" class="btn btn-float bgm-white m-btn ">
            <a class="{{ trans('messages.theme')}}" href="{{ action('General_TimeTable\SampleTimeTableController@downloadPDF',["yearID" => $year->year_id,"semesterID" => $semester->semester_id,"departmentID" => $department->department_id,'levelID' => $level->level_id]) }}">
                <i class="zmdi zmdi-download"></i></a></button>
    </div>
</div>

<br><br>
