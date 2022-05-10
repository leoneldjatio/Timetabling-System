@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="container" style="width: inherit">
            <form role="form" enctype="multipart/form-data" method="post" action="/compulsarysample" id="compulsary_generation">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-sm-3 m-b-25">
                        <select  id="level" class="@lang('messages.select_text_color') selectpicker" name="level" required>
                            <option value="" disabled selected>@lang('messages.select_level')</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->level_id}}" >{{$level->level_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3 m-b-25">
                        <select  id="semester_id" name="semester" class="@lang('messages.select_text_color') selectpicker" required>
                            <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle" disabled
                                    selected>@lang('messages.select_semester')</option>
                            <option value="@lang('messages.semester_one')" data-icon="{{ asset('avatars/first.png') }}"
                                    class="left circle">@lang('messages.semester_one')</option>
                            <option value="@lang('messages.semester_two')" data-icon="{{ asset('avatars/second.png') }}"
                                    class="left circle">@lang('messages.semester_two')</option>
                        </select>
                    </div>
                    <div class="col-sm-3 m-b-25">
                        <select data-live-search="true" id="year" name="year" class="@lang('messages.select_text_color') selectpicker" required>
                            <option value="" data-icon="{{ asset('avatars/icon.png') }}" class="left circle" disabled
                                    selected>@lang('messages.select_year')</option>
                            @foreach($allYears as $academic_year)
                                <option value="{{ $academic_year->year_value}}"
                                        data-icon="{{ asset('avatars/teacher.jpeg') }}"
                                        class="left circle">{{$academic_year->year_value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2 m-b-25">
                        <button type="submit"
                                class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px">
                            <i
                                    class="zmdi zmdi-forward"></i>@lang('messages.view_timetable')
                        </button>
                    </div>
                </div>
            </form>
            <div id="compulsary">
                <br>
                <br><br>

                <div class="row" id="student_info">
                    <div class="col-sm-4 m-b-25">
                        <div class="icon-block">
                            <p>@lang('messages.name'): {{ Auth::user()->user_name }}</p>
                            <p>@lang('messages.matricule') N<sup>o</sup>: {{ Auth::user()->email}} </p>
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
                </div>
                <br>
                <div id="card_alert" class="{{ trans('messages.logout_text_color'). 'center' }} alert alert-warning" style="border-radius: 30px">
                    <div class=" text-center">
                        <i class="zmdi zmdi-notifications-active"></i>@lang('messages.load_compulsary')
                    </div>

                </div>
                <br><br>
            </div>
        </div>
        <script>
            $('#compulsary_generation').submit(function (e) {
                $.ajaxSetup({
                    global: true,
                });
                var form = $(this);
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: '/compulsarysample',
                    data: form.serialize(),
                    success: function (data) {
                        form.hide('slow');
                        $('#compulsary').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                        $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                        form.slideDown('slow');
                        $('html, body').delay('5300').animate({scrollTop: $("#compulsary").offset().top + 200}, 2000);
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
                            html: " Compulsary TimeTable Loaded Successfully",
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
