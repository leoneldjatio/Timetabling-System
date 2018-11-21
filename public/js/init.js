/**
 * @author Go-Groups LTD
 */

//initialize jquery
$(document).ready(function () {
    //ajax call to controller method responsible for sending sms to Go-Groups for support
    //ajax call to send assigments to lecturer
    $('#sms_support').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        form = $(this);
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/sms',
            data: form.serialize(),
            success: function (data) {
                form.hide('slow').delay('5000');
                form.show('slow');
            },
            error: function () {
                swal({
                    title: " Error! ",
                    type: "success",
                    html: " FAILDED TO SEND SMS",
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal({
                    title: " Complete!",
                    type: "success",
                    html: "SMS SENT SUCCESSFULLY",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            },
        });
    });
    //ajax call to send assigments to lecturer
    $('#email_support').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        form = $(this);
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/email',
            data: form.serialize(),
            success: function (data) {
                form.hide('slow').delay('4000');
                form.show('slow');
            },
            error: function () {
                swal({
                    title: " Error! ",
                    type: "success",
                    html: " FAILDED TO SEND EMAIL",
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal({
                    title: " Complete!",
                    type: "success",
                    html: "EMAIL SENT SUCCESSFULLY",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            },
        });
    });
    //ajax call to permit the administrator to generate a general timetable for the university
    $('#generation').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        form = $(this);
        e.preventDefault();
        swal({
            type: '',
            text:'Please Wait',
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
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal.close();
                swal({
                    title: " Complete!",
                    type: "success",
                    html: " General TimeTable Generated Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            },
        });
    });
    //ajax call to permit the administrator to generate a general timetabl for the university
    $('#main').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        form = $(this);
        e.preventDefault();
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
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal({
                    title: " Complete!",
                    type: "success",
                    html: " General TimeTable Generated Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            },
        });
    });
    //ajax call to permit the administrator to view the general timetable of the various academic years
    $('#view_general').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        var form = $(this);
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/viewtimetable',
            data: form.serialize(),
            success: function (data) {
                form.hide('slow');
                $('#general').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                form.slideDown('slow');
                $('html, body').delay('5300').animate({scrollTop: $("#general").offset().top + 200}, 2000);
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
                    html: " General TimeTable Loaded Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });
    //ajax call to get the student time table
    $('#student_generation').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        var form = $(this);
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/student',
            data: form.serialize(),
            success: function (data) {
                form.hide('slow');
                $('#student').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                form.slideDown('slow');
                $('html, body').delay('5300').animate({scrollTop: $("#student").offset().top + 200}, 2000);
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
                    html: " Student TimeTable Loaded Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });

    //ajax call to get the lecturers timetable
    $('#teacher_generation').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        var form = $(this);
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/teacher',
            data: form.serialize(),
            success: function (data) {
                form.hide('slow');
                $('#teacher').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                form.slideDown('slow');
                $('html, body').delay('5300').animate({scrollTop: $("#teacher").offset().top + 200}, 2000);
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
                    html: " Teacher TimeTable Loaded Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });
    //ajax call to get departmental timetable
    $('#department_generation').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        var form = $(this);
        e.preventDefault();
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
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });
    //ajax call to get faculty timetable
    $('#faculty_generation').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        var form = $(this);
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/faculty',
            data: form.serialize(),
            success: function (data) {
                form.hide('slow');
                $('#faculty').html('<div>' + '<br>' + data + '</div>').hide('slow').delay('3100').show('slow');
                $('#card_alert').fadeOut('slow').delay('4000').slideDown('slow');
                form.slideDown('slow');
                $('html, body').delay('5300').animate({scrollTop: $("#faculty").offset().top + 200}, 2000);
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
                    html: " Faculty TimeTable Loaded Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });
    //ajax call to edt timetable
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
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal({
                    title: " Complete!",
                    type: "success",
                    html: " Lecturer TimeTable Loaded Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });
    //ajax call to edt timetable
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
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal({
                    title: " Complete!",
                    type: "success",
                    html: " Compulsary TimeTable Loaded Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });
    //ajax call to edt timetable
    $('#Configure').submit(function (e) {
        $.ajaxSetup({
            global: true,
        });
        var form = $(this);
        e.preventDefault();
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
                    showCloseButton: "okay!!"
                });
            },
            complete: function () {
                swal({
                    title: " Complete!",
                    type: "success",
                    html: " Configurations Done Successfully",
                    timer: 3000,
                    showConfirmButton: false,
                    showCloseButton: true,
                });

            }
        })
    });

    $('#welcome_message').hide('fast').delay('5000').show('slow').delay('3000').hide('slow');

    $('.help').click(function () {
        $('.tap-target').tapTarget('open');
    });

    $('.tap-target').tapTarget('close');

    $(".button-collapse").sideNav();
    //initialize  amterialize tabs
    $('ul.tabs').tabs();
    //
    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // Does not change width of dropdown to that of the activator
            hover: false, // Activate on hover
            gutter: 5, // Spacing from edge
            belowOrigin: false, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left of button
            stopPropagation: false // Stops event propagation
        }
    );

    $('.dropdown-button1').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // Does not change width of dropdown to that of the activator
            hover: false, // Activate on hover
            gutter: 5, // Spacing from edge
            belowOrigin: false, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left of button
            stopPropagation: false // Stops event propagation
        }
    );


    $('select').material_select();
    

    $('.datepicker').pickadate({
        selectMonths: false, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        closeOnSelect: true, // Close upon selecting a date,
    });

    $('.modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        inDuration: 800, // Transition in duration
        outDuration: 800, // Transition out duration

    });

    $('ul.tabs').tabs();


    var opts = {
        lines: 13, // The number of lines to draw
        length: 38, // The length of each line
        width: 17, // The line thickness
        radius: 45, // The radius of the inner circle
        scale: 1, // Scales overall size of the spinner
        corners: 1, // Corner roundness (0..1)
        color: '#ffffff', // CSS color or array of colors
        fadeColor: 'transparent', // CSS color or array of colors
        opacity: 0.25, // Opacity of the lines
        rotate: 0, // The rotation offset
        direction: 1, // 1: clockwise, -1: counterclockwise
        speed: 1, // Rounds per second
        trail: 60, // Afterglow percentage
        fps: 20, // Frames per second when using setTimeout() as a fallback in IE 9
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        className: 'spinner', // The CSS class to assign to the spinner
        top: '50%', // Top position relative to parent
        left: '50%', // Left position relative to parent
        shadow: none, // Box-shadow for the lines
        position: 'absolute' // Element positioning
    };

    var target = document.getElementById('foo');
    var spinner = new Spinner(opts).spin(target);
});