
        <select id="{{ 'select-' . $timeSlot->time_slot_id }}"  class="{{$timeSlot->time_slot_id}}" name="{{$teacher->teacher_id}}">
            <option value="none" selected disabled="disabled" >SELECT COURSE</option>
            @foreach($lectCourses as $course)
                <option value="{{ $course->course_code}}" >{{$course->course_code}}</option>
            @endforeach
            <option value="delete">@lang('messages.delete')</option>
        </select>

        <script>
            var selectId = '#select-' + JSON.parse(@json($timeSlot->time_slot_id));
            $(selectId).change(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var data = {oldcourse: @json(strip_tags($oldCourse))};
                var teachername ={teacher_Name:"{{$teacherName}}"};
                var newCourse = $(this).val();
                var tdInfo = {teacherID: $(this).attr('name'), timeSlotID: this.className,teacherName:teachername.teacher_Name,newCourseCode:newCourse,oldCourseCode:data.oldcourse};
                swal({
                    title: '<strong>DO YOU WANT TO <u>SAVE</u> CHANGES?</strong>',
                    type: 'question',
                    html:
                    'Classrooms will <b>AUTOMATICALLY</b>, ' +
                    'be Allocated Respecting Necessary Constraints ',
                    customClass: 'swal-border',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText:
                        '<i class="zmdi zmdi-save"></i>SAVE',
                    confirmButtonAriaLabel: 'SAVE',
                    cancelButtonText:
                        '<i class="zmdi zmdi-close"></i>CANCEL',
                    cancelButtonAriaLabel: 'CANCEL',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            type: 'post',
                            url: '/swap',
                            data: JSON.stringify(tdInfo),
                            contentType: 'application/json; charset=utf-8',
                            success: function (data,txt,xhr) {
                                if(xhr.status === 202){
                                    var id = '.' + tdInfo.timeSlotID;
                                    $(id.toString()).text(data.state);
                                }else {
                                    var id = '.' + tdInfo.timeSlotID;
                                    $(id.toString()).html(data);
                                }
                            },
                        })
                    },
                }).then((result) => {
                    if (result.dismiss == "cancel") {
                        tdId = '.' + tdInfo.timeSlotID;
                        $(tdId).text(data.oldcourse);
                    }
                });

            })

        </script>