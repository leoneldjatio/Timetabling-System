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
use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Student;
use App\Year;
use App\Http\Requests\Department_TimeTable\DepartmentTimeTableRequest;
use PDF;

class DepartmentTimeTableController extends Controller
{
    /**
     * DepartmentTimeTableController constructor.
     */

    public function  __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:department');
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

        return view('departmental_timetable.departmental_timetable', compact('year', 'semester', 'department','allYears'));


    }

    /**
     * @param DepartmentTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadPastTimeTable(DepartmentTimeTableRequest $request)
    {

        $allYears = Year::all()->sortByDesc('year_id');
        $year = Year::where('year_value', $request->get('year'))->get()->first();
        $semester = $year->semesters()->where('semester_name', $request->get('semester'))->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        $student = Student::where('student_name', strtoupper(Auth::user()->user_name))->get()->first();
        $department = $student->departments()->get()->first();
        return view('departmental_timetable.departmental_table', compact('year', 'semester', 'department', 'allYears'));


    }

    /**
     * @param $yearID
     * @param $semesterID
     * @param $teacherID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadPDF($yearID,$semesterID,$departmentID){
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        $department = Department::where('department_id', $departmentID)->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        $pdf = PDF::loadView('departmental_timetable.download_table', compact('year', 'semester', 'department'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if($numTimeSlots >5){
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('departmenttimetable.pdf');
    }

}
