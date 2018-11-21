@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="container">
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
            <div id="edit">
                <div class="row" id="teacher_info">
                    <div class="col-sm-3 m-b-25">
                        <div class="icon-block">
                            <p>@lang('messages.semester') N<sup>o</sup>: {{ $semester->semester_name }} </p>
                            <p>@lang('messages.academic_year'): {{ $year->year_value }}</p>
                        </div>
                    </div>

                    <div class="col-sm-5 m-b-25">
                        <div class="icon-block text-center">
                            <br> <br>
                            <img src="{{ asset('avatars/icon.png') }}" alt="university">
                            <br>
                        </div>
                    </div>

                    <div class="col-sm-4 m-b-25">
                        <div class="icon-block">
                            <p>@lang('messages.department'): {{ $department->department_name}}</p>
                            <p>@lang('messages.date'): {{ date('d/m/Y h:i:s a', time()) }}</p>
                        </div>
                    </div>
                </div>
                <div class="center">
                    <h5 style="text-transform: capitalize;color: grey;">@lang('messages.t_caption')</h5>
                </div>
                <br>

                <div id="card_alert" class="{{ trans('messages.logout_text_color'). 'center' }} alert alert-warning" style="border-radius: 30px">
                    <div class=" text-center">
                        <i class="zmdi zmdi-notifications-active"></i>@lang('messages.load_timetable')
                    </div>

                </div>

                <br><br>
            </div>
        </div>
    </div>
    <script>
        $('#edit_generation').submit(function (e) {
            $.ajaxSetup({
                global: true,
            });
            var form = $(this);
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '/edittimetable',
                data: form.serialize(),
                success: function (data) {
                    form.hide('slow');
                    $('#edit').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                    $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                    form.slideDown('slow');
                    $('html, body').delay('5300').animate({scrollTop: $("#edit").offset().top + 200}, 2000);
                },
                error: function () {
                    swal({
                        title: " Error! ",
                        type: "success",
                        html: " Failed To Load Resource",
                        customClass: 'swal-border',
                        showCloseButton: "okay!!"
                    });
                },
                complete: function () {
                    swal({
                        title: " Complete!",
                        type: "success",
                        html: " Lecturer TimeTable Loaded Successfully",
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

