@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="container">
            <form  role="form" enctype="multipart/form-data" class="col s12" method="POST" action="/configuration" id="Configure">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="card-body card-padding">
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="fg-line form-group">
                                    <label>@lang('messages.number_of_periods')</label>
                                    <input id="dperiods" name="dperiods" type="number" class="validate form-control input-sm" required>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="fg-line form-group">
                                    <label>@lang('messages.period_length')</label>
                                    <input id="pduration" name="pduration" type="number" class="validate form-control input-sm" required>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="fg-line form-group">
                                    <label>@lang('messages.teaching_duration')</label>
                                    <input id="lperiods" name="lperiods" type="number" class="validate form-control input-sm" required>
                                </div>
                            </div>
                        </div>
                     <div class="row">
                       <div class="col-sm-4 m-b-25">
                           <p class="f-500 c-black m-b-15">Select Lecture Days</p>
                           <select class="selectpicker" multiple name="weekdays[]" required>
                               <option value="" disabled selected></option>

                               @foreach($week_days as $week_day)
                                   <option value="{{$week_day}}" class="blue">{{$week_day}}</option>
                               @endforeach
                           </select>

                     </div>
                         <div class="col-sm-4 m-b-25">
                             <p class="f-500 c-black m-b-15">Split Lecture Capacity</p>
                             <select class="selectpicker" name="should_split" required>
                                 <option value="" disabled selected></option>
                                 <option value="true" class="blue">@lang('messages.true')</option>
                                 <option value="false" class="blue">@lang('messages.false')</option>
                             </select>

                         </div>

                         <div class="col-xs-4">
                             <div class="fg-line form-group">
                                 <label>@lang('messages.start_time')</label>
                                 <input  id="start_time" name="start_time" type="text" class="validate form-control input-sm time-picker" required>
                             </div>
                         </div>
                    </div>
                </div>

                <div class="text-center">

                    <button class="btn btn-default btn-icon-text palette-Teal-50 bg" style="border-radius: 30px" type="submit"><i class="zmdi zmdi-settings-square"></i>@lang('messages.click_to_configure')</button>

                </div>
            </form>
        </div>
    </div>
    </div>

    <script type="text/javascript">
        $('#Configure').submit(function (e) {
            e.preventDefault();
            $.ajaxSetup({
                global: true,
            });
            var form = $(this);

            $.ajax({
                type: 'post',
                url: '/configuration',
                data: form.serialize(),
                success: function (data) {
                    form.hide('slow');
                    $('#Configure').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                    $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                    form.slideDown('slow');
                    $('html, body').delay('5300').animate({scrollTop: $("#Configure").offset().top + 200}, 2000);
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
                        html: " Configurations Done Successfully",
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