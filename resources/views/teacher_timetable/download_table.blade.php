<html>
<head>
</head>
<body>
<div class="{{ trans('messages.widget_theme') . '  center' }}">
    <div class=" {{ trans('messages.alert_theme') . ' card-content center'}}">
        @lang('messages.university')<br>  @lang('messages.academic_year'): {{ $year->year_value }} <br> {{ Auth::user()->user_name . '\'s ' }} Timetable
    </div>
</div>
<br><br>

<div class="center">
    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
</div>
<br>
<table class="striped responsive-table">
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
                        @foreach($teacher->courses()->get() as $course)
                            @if($allocation->course_code == $course->course_code && $allocation->teacher_name == $teacher->teacher_name)
                                {{  $allocation->course_code . ' ' .  $allocation->room_name  }}
                                <br>{{ $allocation->teacher_name }}<br>
                            @endif
                        @endforeach
                    @endforeach
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
<br>
    <div class=" {{ trans('messages.alert_theme') . ' card-content center'}}">
        <p>@lang("messages.university_slogan")</p>
    </div>

<style type="text/css">
    html {
        font-family: sans-serif;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%
    }
    p{
        position: absolute;
        right: 0px;
    }

    body {
        margin: 0
    }
    table.striped th {
        background-color: @lang('messages.widget_theme');
        color: white;
        border-color: white;
        outline-color: white;
        border-width: 3px 3px 3px 3px;
        padding: 20px 25px 25px 25px;
    }

    table.striped td {
        border-color: white;
        outline-color: white;
        border-width: 3px 3px 3px 3px
    }

    table.striped > tbody > tr:nth-child(odd) {
        background-color: #f2f2f2;
        border-color: white;
        outline-color: white;
    }

    table.striped > tbody > tr > td {
        border-radius: 0
    }

</style>
</body>
</html>



