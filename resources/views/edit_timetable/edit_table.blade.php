@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="container" style="width: inherit">
    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="/edittimetable" id="edit_generation">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="card-body card-padding">
            <div class="row">
                <div class="col-sm-3 m-b-25">
                    <select class="selectpicker @lang('messages.select_text_color')" id="semester_id" name="semester" required>
                        <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle " disabled
                                selected>@lang('messages.select_semester')</option>
                        <option value="@lang('messages.semester_one')" data-icon="{{ asset('avatars/first.png') }}"
                                class="left circle">@lang('messages.semester_one')</option>
                        <option value="@lang('messages.semester_two')" data-icon="{{ asset('avatars/second.png') }}"
                                class="left circle ">@lang('messages.semester_two')</option>
                    </select>
                </div>
                <div class="col-sm-3 m-b-25">
                    <select class="selectpicker" data-live-search="true" id="year" name="year" class="@lang('messages.select_text_color')" required>
                        <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle" disabled
                                selected>@lang('messages.select_year')</option>
                        @foreach($allYears as $academic_year)
                            <option value="{{ $academic_year->year_value}}"
                                    data-icon="{{ asset('avatars/teacher.jpeg') }}"
                                    class="left circle">{{$academic_year->year_value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 m-b-25">
                    <select class="selectpicker" data-live-search="true" id="teacher" class="@lang('messages.select_text_color')" name="teacher" required>
                        <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle" disabled
                                selected>@lang('messages.select_teacher')</option>
                        @foreach($department->teachers()->get() as $depTeacher)
                            <option value="{{ $depTeacher->teacher_id}}"
                                    data-icon="{{ asset('avatars/lecturer.png') }}"
                                    class="left circle">{{$depTeacher->teacher_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-field col-sm-3 m-b-25">
                    <button type="submit" class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px"><i class="zmdi zmdi-forward"></i>@lang('messages.view_timetable')</button>
                </div>
            </div>
        </div>
    </form>
<div class="alert alert-success alert-dismissible" role="alert" style="border-radius: 30px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ $teacher->teacher_name. '\'s ' }}@lang('messages.generate_teacher')
</div>
<br><br>
<div class="row" id="teacher_info">
    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.name'): {{ $teacher->teacher_name}}</p>
            <p>@lang('messages.email') : {{ Auth::user()->email}} </p>
            <p>@lang('messages.edited'): {{ date('d/m/Y h:i:s a', time()) }}</p>
        </div>
    </div>

    <div class="col-sm-4 m-b-25" >
        <div class="icon-block text-center">
            <br> <br>
            <img src="{{ asset('avatars/icon.png') }}" alt="university">
            <br>
        </div>
    </div>

    <div class="col-sm-4 m-b-25">
        <div class="icon-block">
            <p>@lang('messages.department'): {{ $department->department_name}}</p>
            <p>@lang('messages.semester') N<sup>o</sup>: {{ $semester->semester_name }} </p>
            <p>@lang('messages.academic_year'): {{ $year->year_value }}</p>
        </div>
          </div>
        </div>

<div class="center">
    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
</div> <br>
<div class="table-responsive">
<table class="table table-bordered" id="print" border="2">
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
            <td class="@lang('messages.table_theme')"><b><a style="color:white;">{{ $week_day->week_day_name }}</a></b></td>
            @foreach($week_day->timeSlots()->get() as $time_slot)
                <td bgcolor="#add8e6" class="{{ $time_slot->time_slot_id }}" id="{{ $teacher->teacher_name }}" data-name-type="{{$semester->semester_name}}">
                    @foreach($time_slot->allocations()->get() as $allocation)
                        @foreach($courses as $course)
                            @if($allocation->course_code == $course->course_code && $allocation->teacher_name == $teacher->teacher_name)
                                @if($depCourses->contains('course_code',$allocation->course_code))
                                    <font color="#8b4513" ><b>{{  $allocation->course_code }}<br>-{{$allocation->room_name}}</b></font>
                                @else
                                    <p>{{$allocation->course_code }}<br>-{{$allocation->room_name}}</p>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $("td:has(p)").css({
                                                'background-color': 'red',
                                                "color": "white"
                                            }).attr('contenteditable', 'false');
                                        })
                                    </script>
                                @endif
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

<div class="card-content ">
    <button type="" class="btn btn-float bgm-white m-btn ">
        <a class="" href="{{ action('General_TimeTable\EditTimeTableController@downloadPDF',["year" => $year->year_id,"semester" => $semester->semester_id,"departmentID" => $department->department_id,'teacherID' => $teacher->teacher_id]) }}">
            <i class="zmdi zmdi-download"></i>
        </a>
    </button>
</div>
        </div>
    </div>
<script type="text/javascript">
    $("td:not(:has(p,a))").one("click", function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var semester = $(this).data('name-type');
        var s = this.innerHTML;
        var fields = s.split(/-/);
        var oldCourse = fields[0];
        var loadInfo = {teacherName: this.id, timeSlotID: this.className,oldCourseCode:oldCourse,Semester:semester};
        swal({
            type: '',
            text:'Please Wait',
            customClass: 'swal-border',
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
        swal.showLoading();
        $.ajax({
            type: 'post',
            url: '/loadvalid',
            data: JSON.stringify(loadInfo),
            contentType: 'application/json; charset=utf-8',
            success: function (data,txt,xhr) {
                if(xhr.status == 202){
                    var id = '.' + loadInfo.timeSlotID;
                    $(id.toString()).html(data.status);
                }else {
                    var id = '.' + loadInfo.timeSlotID;
                    $(id.toString()).html(data);
                }

            },
            complete: function () {
                swal.close();
            }
        });

        $(this).css({"background-color": "inherit", "color": "black", "outline-color": "DodgerBlue"});

    })


</script>
    <!--<script language="javascript">
        setInterval(function(){
            window.location.reload(1);
        }, 60000);
    </script>-->
<br><br>

@endsection

