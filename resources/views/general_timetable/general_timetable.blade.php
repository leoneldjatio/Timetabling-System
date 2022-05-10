@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="container" id="general" style="width: inherit">
                <div role="tabpanel">
                    <ul class="tab-nav" role="tablist">
                        <li class="active" id="gen">
                            <a href="#generate"  aria-controls="generate" role="tab" data-toggle="tab">
                                <i  class="zmdi zmdi-balance"></i> @lang('messages.gen_timetable')</a>
                        </li>
                        <li style="margin-left: 300px" id="gen">
                            <a href="#maintain" aria-controls="maintain" role="tab" data-toggle="tab">
                                <i  class="zmdi zmdi-balance"></i> @lang('messages.main_timetable')</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="generate" role="tabpanel" class="tab-pane active animated bounceInLeft">
                            <form  role="form" enctype="multipart/form-data" method="post" action="/generate" id="generaton">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-sm-6 m-b-30">
                                        <select id="semester_id" name="semester" class="@lang('messages.select_text_color') selectpicker" required>
                                            <option value="" disabled selected data-icon="{{ asset('avatars/icon.png') }}"
                                                    class="left circle">@lang('messages.select_semester')</option>
                                            <option value="@lang('messages.semester_one')"
                                                    data-icon="{{ asset('avatars/first.png') }}"
                                                    class="left circle">@lang('messages.semester_one')</option>
                                            <option value="@lang('messages.semester_two')"
                                                    data-icon="{{ asset('avatars/second.png') }}"
                                                    class="left circle">@lang('messages.semester_two')</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 m-b-30">
                                        <select data-live-search="true" id="year" name="year" class="@lang('messages.select_text_color') selectpicker" required>
                                            <option value="" disabled selected data-icon="{{ asset('avatars/icon.png') }}"
                                                    class="left circle">@lang('messages.select_year')</option>
                                            @foreach($current_year as $academic_year)
                                                <option value="{{ $academic_year}}" data-icon="{{ asset('avatars/generate.jpeg') }}"
                                                        class="left circle">{{$academic_year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br><br><br> <br><br>
                                    <div class="text-center">
                                        <button class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px" type="submit"
                                                id="genButton"><i
                                                    class="zmdi zmdi-archive"></i>@lang('messages.generate')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div  id="maintain" role="tabpanel" class="tab-pane animated bounceInRight">
                            <form class="text-center" role="form" enctype="multipart/form-data" method="post" action="/maintain" id="maintainance">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-sm-6 m-b-30">
                                        <select id="semester_id" name="semester" class="@lang('messages.select_text_color') selectpicker" required>
                                            <option value="" disabled selected data-icon="{{ asset('avatars/icon.png') }}"
                                                    class="left circle">@lang('messages.select_semester')</option>
                                            <option value="@lang('messages.semester_one')"
                                                    data-icon="{{ asset('avatars/first.png') }}"
                                                    class="left circle">@lang('messages.semester_one')</option>
                                            <option value="@lang('messages.semester_two')"
                                                    data-icon="{{ asset('avatars/second.png') }}"
                                                    class="left circle">@lang('messages.semester_two')</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 m-b-30">
                                        <select  data-live-search="true"  id="year" name="year" class="@lang('messages.select_text_color') selectpicker" required>
                                            <option value="" disabled selected data-icon="{{ asset('avatars/icon.png') }}"
                                                    class="left circle">@lang('messages.select_year')</option>
                                            @foreach($allYears as $academic_year)
                                                <option value="{{ $academic_year->year_value}}"
                                                        data-icon="{{ asset('avatars/generate.jpeg') }}"
                                                        class="left circle">{{$academic_year->year_value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br><br><br> <br><br>
                                    <div class="text-center">
                                        <button class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px" type="submit"
                                                id="genButton"><i
                                                    class="zmdi zmdi-archive"></i>@lang('messages.maintain')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </div>
    </div>
    <script>
        $('#generaton').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                global: true,
            });
            form = $(this);
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
                url: '/generate',
                data: form.serialize(),
                success: function (data) {
                    form.hide('slow');
                    $('#general').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                    $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                    $('html, body').delay('5300').animate({scrollTop: $("#general").offset().top + 200}, 2000);
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
                    swal.close();
                    swal({
                        title: " Complete!",
                        type: "success",
                        html: " General TimeTable Generated Successfully",
                        customClass: 'swal-border',
                        timer: 3000,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                },
            });
        });

    </script>
    <script>
        $('#maintainance').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                global: true,
            });
            form = $(this);
            $.ajax({
                type: 'post',
                url: '/maintain',
                data: form.serialize(),
                success: function (data) {
                    form.hide('slow');
                    $('#general').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                    $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                    $('html, body').delay('5300').animate({scrollTop: $("#general").offset().top + 200}, 2000);
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
                        html: " General TimeTable Generated Successfully",
                        customClass: 'swal-border',
                        timer: 3000,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                },
            });
        });
    </script>
@endsection
