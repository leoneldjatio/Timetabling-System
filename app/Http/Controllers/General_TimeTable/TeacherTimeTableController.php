<?php

namespace App\Http\Controllers\General_TimeTable;
/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: ewangclarks
 * Date: 13/01/17
 * Time: 3:17 PM
 *
 */
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher_TimeTable\TeacherTimeTableRequest;
use App\Teacher;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class TeacherTimeTableController extends Controller
{
    /**
     * TeacherTimeTableController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadRecentTimeTable()
    {
        $allYears =Year::all()->sortByDesc('year_id');
        $year = $allYears->first();
        $semester = $year->semesters()->get()->last();
        $teacher = Teacher::where('teacher_name', strtoupper(Auth::user()->user_name))->get()->first();
        $department = $teacher->departments()->get()->first();
        return view('teacher_timetable.teacher_timetable', compact('year', 'semester','department', 'teacher', 'allYears'));
    }

    /**
     * @param TeacherTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadPastTimeTable(TeacherTimeTableRequest $request)
    {

        $allYears =Year::all()->sortByDesc('year_id');
        $year = Year::where('year_value', $request->get('year'))->get()->first();
        $semester = $year->semesters()->where('semester_name', $request->get('semester'))->get()->first();
        $teacher = Teacher::where('teacher_name', strtoupper(Auth::user()->user_name))->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        return view('teacher_timetable.teacher_table', compact('year', 'semester', 'teacher', 'allYears'));

    }

    /**
     * @param TeacherTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadPDF($yearID,$semesterID,$teacherID){
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        $teacher = Teacher::where('teacher_id', $teacherID)->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        $pdf = PDF::loadView('teacher_timetable.download_table', compact('year', 'semester', 'teacher'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if($numTimeSlots >5){
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('teachertimetable.pdf');
   }
}
