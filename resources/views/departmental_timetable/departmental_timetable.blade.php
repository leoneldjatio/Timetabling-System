@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="container">
            <form role="form" enctype="multipart/form-data" method="post" action="/department" id="department_generation">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-sm-5 m-b-25">
                        <select id="semester_id" name="semester" class="@lang('messages.select_text_color') selectpicker" required>
                            <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle" disabled selected>@lang('messages.select_semester')</option>
                            <option value="@lang('messages.semester_one')" data-icon="{{ asset('avatars/first.png') }}" class="left circle">@lang('messages.semester_one')</option>
                            <option value="@lang('messages.semester_two')" data-icon="{{ asset('avatars/second.png') }}" class="left circle">@lang('messages.semester_two')</option>
                        </select>
                    </div>
                    <div class="col-sm-5 m-b-25">
                        <select data-live-search="true" id="year" name="year" class="@lang('messages.select_text_color') selectpicker" required>
                            <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle" disabled selected>@lang('messages.select_year')</option>
                            @foreach($allYears as $academic_year)
                                <option value="{{ $academic_year->year_value}}" data-icon="{{ asset('avatars/department.jpeg') }}" class="left circle">{{$academic_year->year_value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2 m-b-25">
                        <div class="input-field col-sm-3 m-b-25" >
                            <button type="submit" class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px"><i class="zmdi zmdi-forward"></i>@lang('messages.view_timetable')</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="department">
                <br>
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
                            <p>@lang('messages.department'): {{ $department->department_name}}</p>
                            <p>@lang('messages.semester') N<sup>o</sup>: {{ $semester->semester_name }} </p>
                            <p>@lang('messages.academic_year'): {{ $year->year_value }}</p>
                        </div>
                    </div>
                </div>
                <div class="center">
                    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
                </div>
                <br>
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
                            <i class="zmdi zmdi-download"></i></a></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#department_generation').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                global: true,
            });
            var form = $(this);
            $.ajax({
                type: 'post',
                url: '/department',
                data: form.serialize(),
                success: function (data) {
                    form.hide('slow');
                    $('#department').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                    $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                    form.slideDown('slow');
                    $('html, body').delay('5300').animate({scrollTop: $("#department").offset().top + 200}, 2000);
                },
                error: function () {
                    swal({
                        title: " Error! ",
                        type: "success",
                        html: " Failed To Load Resource",
                        showCloseButton: "okay!!"
                    });
                },
                complete: function () {
                    swal({
                        title: " Complete!",
                        type: "success",
                        html: " Department TimeTable Loaded Successfully",
                        customClass: 'swal-border',
                        timer: 3000,
                        showConfirmButton: false,
                        showCloseButton: true,
                    });

                }
            })
        });
    </script>
@endsection
