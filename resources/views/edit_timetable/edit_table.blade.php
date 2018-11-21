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
                <td class="{{ $time_slot->time_slot_id }}" id="{{ $teacher->teacher_name }}">
                    @foreach($time_slot->allocations()->get() as $allocation)
                        @foreach($courses as $course)
                            @if($allocation->course_code == $course->course_code && $allocation->teacher_name == $teacher->teacher_name)
                                @if($depCourses->contains('course_code',$allocation->course_code))
                                    {{  $allocation->course_code }}<br>-{{$allocation->room_name}}
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
<script type="text/javascript">
    $("td:not(:has(p))").one("click", function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var s = this.innerHTML;
        var fields = s.split(/-/);
        var oldCourse = fields[0];
        var loadInfo = {teacherName: this.id, timeSlotID: this.className,oldCourseCode:oldCourse};
        swal({
            customClass: 'swal-wide',
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

        //$("td:not(:has(p))").unbind("click");
    })

</script>
<br><br>
    </div>
</div>

