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
use App\Http\Requests\Student_TimeTable\StudentTimeTableRequest;

use App\Student;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Department;

class StudentTimeTableController extends Controller
{
    /**
     * StudentTimeTableController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadRecentTimeTable()
    {
        $allYears =Year::all()->sortByDesc('year_id');
        $year = $allYears->first();
        $semester = $year->semesters()->get()->last();
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        return view('student_timetable.student_timetable', compact('year', 'semester','department', 'student','allYears'));
    }

    /**
     * @param StudentTimeTableRequest $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadPastTimeTable(StudentTimeTableRequest $request)
    {
        $allYears =Year::all()->sortByDesc('year_id');
        $year = Year::where('year_value', $request->get('year'))->first();
        $semester=$year->semesters()->where('semester_name', $request->get('semester'))->first();
        $student = Student::where('student_name', strtoupper(Auth::user()->user_name))->get()->first();
        $department = $student->departments()->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        return view('student_timetable.student_table', compact('year', 'semester','department', 'student','allYears'));
    }

    /**
     * @param $yearID
     * @param $semesterID
     * @param $studentID
     * @return mixed
     */
    public function downloadPDF($yearID,$semesterID,$studentID){
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        $student = Student::where('student_id', $studentID)->get()->first();
        $pdf = PDF::loadView('student_timetable.download_table', compact('year', 'semester', 'student'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if($numTimeSlots >5){
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('studenttimetable.pdf');
    }
}
