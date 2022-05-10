<html>
<head>
</head>
<body>
@inject('slots','App\TimeSlot')
<div class="container">
    <div class="row">
        <div class="col-sm-2 m-b-25">
            <div class="icon-block">
                <p>@lang('messages.name'): {{ Auth::user()->user_name }}</p>
                <p>@lang('messages.email') : {{ Auth::user()->email}} </p>
                   <p>@lang('messages.printed_date'): {{ date('d/m/Y h:i:s a', time()) }}</p>
            </div>
        </div>

        <div id="pos">
                <p>@lang('messages.faculty'): {{$faculty->faculty_name}}</p>
                <p>@lang('messages.semester') N<sup>o</sup>: {{ $semester->semester_name }} </p>
                <p>@lang('messages.academic_year'): {{ $year->year_value }}</p>
        </div>
    </div>
</div>
<div class="center">
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
                <td class="@lang('messages.table_theme')"><b><a style="color:black">{{ $week_day->week_day_name }}</a></b></td>
                @foreach($week_day->timeSlots()->get() as $time_slot)
                    <td>

                        @foreach($time_slot->allocations()->get() as $allocation)

                            @foreach($faculty_courses as $course)
                                @if($allocation->course_code == $course->course_code)
                                    <a style="color: brown">{{  $allocation->course_code . ' ' .  $allocation->room_name  }}</a>
                                    <br><a style="color: black">{{ $allocation->teacher_name }}</a><br><br>
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
<br>
<div class=" {{ trans('messages.alert_theme') . ' card-content center'}}">
    <p>@lang("messages.university_slogan")</p></div>
<br><br>

<style type="text/css">
    html {
        font-family: sans-serif;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%
    }


    body {
        margin: 0
    }
    table.table-bordered th {
        background-color:grey;
        color:white;
        border-color: white;
        outline-color: white;
        border-width: 3px 3px 3px 3px;
        padding: 20px 25px 25px 25px;
    }

    table.table-bordered td {
        border-color: white;
        outline-color: white;
        border-width: 3px 3px 3px 3px
    }

    table.table-bordered > tbody > tr:nth-child(odd) {
        background-color: #f2f2f2;
        border-color: white;
        outline-color: white;
    }

    table.table-bordered > tbody > tr > td {
        border-radius: 0
    }
    #pos{
        right: 0px;
    }
    .container{
        width: 100%;
    }
    .row{

    }
    .col-sm-2{
        
    }



</style>

</body>
</html>

