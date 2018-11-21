<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
 * redirection routes for handling user authentication
 */

Auth::routes();

// routes for handling all redirections to home
Route::get('/home', 'HomeController@index')->name('home');

/*
 * redirection routes for general timetable creation
 */

Route::get('/generate', 'General_TimeTable\GenerateTimeTableController@create');
Route::post('/generate', 'General_TimeTable\GenerateTimeTableController@generate')->name('general');
Route::get('/chromosome', 'General_TimeTable\GenerateTimeTableController@chromosomeRepresentation');
Route::get('/duplicatechromosome', 'General_TimeTable\GenerateTimeTableController@chromosomeExist');
Route::get('/populate', 'General_TimeTable\GenerateTimeTableController@populate')->name('population');
Route::get('/finalize', 'General_TimeTable\GenerateTimeTableController@finalize')->name('finalize');
Route::get('/courses', 'General_TimeTable\GenerateTimeTableController@mergeCourses')->name('merge');
Route::get('/classrooms', 'General_TimeTable\GenerateTimeTableController@mergeClassrooms')->name('merge');
Route::get('/capacityrangesolver', 'General_TimeTable\GenerateTimeTableController@solveSpaceConstraint')->name('spaceconstraint');
Route::get('/clash', 'General_TimeTable\GenerateTimeTableController@clashFree')->name('removeDuplicate');
Route::get('/reinitialize', 'General_TimeTable\GenerateTimeTableController@reInitialize')->name('reinitialize');
Route::get('/exception', 'General_TimeTable\GenerateTimeTableController@classroomException');
Route::get('/groups', 'General_TimeTable\GenerateTimeTableController@groupFormation');
Route::get('/superexception', 'General_TimeTable\GenerateTimeTableController@superExceptionHandler');
Route::get('/exceptioncheck', 'General_TimeTable\GenerateTimeTableController@isException');
Route::get('/optimal', 'General_TimeTable\GenerateTimeTableController@optimize');
Route::get('/reset', 'General_TimeTable\GenerateTimeTableController@resetTeacherStatus');
Route::get('/maxlevel', 'General_TimeTable\GenerateTimeTableController@getMaxLevel');
Route::get('/iseven', 'General_TimeTable\GenerateTimeTableController@isEven');
Route::get('/stripdigit', 'General_TimeTable\GenerateTimeTableController@stipFirstDigit');
Route::post('/maintain', 'General_TimeTable\GenerateTimeTableController@maintain');
Route::get('/crossover', 'General_TimeTable\GenerateTimeTableController@solveCrossOver');
Route::get('/downloadgeneral', 'General_TimeTable\GenerateTimeTableController@downloadPDF');

/*
 * redirection routes for student timetable loading
 */
Route::get('/student', 'General_TimeTable\StudentTimeTableController@loadRecentTimeTable');
Route::post('/student', 'General_TimeTable\StudentTimeTableController@loadPastTimeTable');
Route::get('/downloadstudenttimetable/{yearID}/{semesterID}/{studentID}', 'General_TimeTable\StudentTimeTableController@downloadPDF');

/*
 * redirection routes for teacher timetable loading
 */

Route::get('/teacher', 'General_TimeTable\TeacherTimeTableController@loadRecentTimeTable');
Route::post('/teacher', 'General_TimeTable\TeacherTimeTableController@loadPastTimeTable');
Route::get('/downloadteachertimetable/{year}/{semester}/{teacher}', 'General_TimeTable\TeacherTimeTableController@downloadPDF');

/*
 * redirection routes for edit timetable loading
 */
Route::get('/edittimetable', 'General_TimeTable\EditTimeTableController@loadRecentTimeTable');
Route::post('/edittimetable', 'General_TimeTable\EditTimeTableController@loadPastTimeTable');
Route::get('/downloadeditedtimetable/{yearID}/{semesterID}/{departmentID}/{teacherID}', 'General_TimeTable\EditTimeTableController@downloadPDF');
Route::post('/swap', 'General_TimeTable\EditTimeTableController@swapper');
Route::get('/check', 'General_TimeTable\EditTimeTableController@constraintcheckerAndResolver');
Route::get('/solve', 'General_TimeTable\EditTimeTableController@capacitySolver');
Route::get('/clashsolver', 'General_TimeTable\EditTimeTableController@clashSolve');
Route::get('/crossoversolver', 'General_TimeTable\EditTimeTableController@solveCrossOver');
Route::post('/loadvalid', 'General_TimeTable\EditTimeTableController@loadValidCourses');



/*
 * redirection routes for configuration view loading
 */
Route::get('/configuration', 'ConfigurationController@create');
Route::post('/configuration', 'ConfigurationController@save');


/*
 * redirection routes for departmental timetable loading
 */
Route::get('/department', 'General_TimeTable\DepartmentTimeTableController@loadRecentTimeTable');
Route::post('/department', 'General_TimeTable\DepartmentTimeTableController@loadPastTimeTable');
Route::get('/downloaddepartmenttimetable/{yearID}/{semesterID}/{departmentID}', 'General_TimeTable\DepartmentTimeTableController@downloadPDF');

/*
 * redirection routes for faculty timetable loading
 */
Route::get('/faculty', 'General_TimeTable\FacultyTimeTableController@loadRecentTimeTable');
Route::post('/faculty', 'General_TimeTable\FacultyTimeTableController@loadPastTimeTable');
Route::get('/downloadfacultytimetable/{yearID}/{semesterID}/{departmentID}', 'General_TimeTable\FacultyTimeTableController@downloadPDF');

/*
 * redirection routes to view the general timetable
 */
Route::get('/viewtimetable', 'General_TimeTable\ViewTimeTableController@create');
Route::get('/downlloadtimetable/{yearID}/{semesterID}', 'General_TimeTable\ViewTimeTableController@downloadPDF');
Route::post('/viewtimetable', 'General_TimeTable\ViewTimeTableController@getTimeTable');


Route::get('/compulsarysample', 'General_TimeTable\SampleTimeTableController@create');
Route::post('/compulsarysample', 'General_TimeTable\SampleTimeTableController@compulsarySample');
Route::get('/levels', 'General_TimeTable\SampleTimeTableController@getLevels');
Route::get('/downloadcompulsarytimetable/{yearID}/{semesterID}/{departmentID}/{levelID}', 'General_TimeTable\SampleTimeTableController@downloadPDF');

/*
 *  redirection route for loging  authenticated uses out of the system
 */


Route::get('/logout', 'Auth\LogoutController@logout');


/*
 * redirection routes for support
 */


Route::post('/email', 'SupportController@sendMail');
Route::post('/sms', 'SupportController@sendSMS');
