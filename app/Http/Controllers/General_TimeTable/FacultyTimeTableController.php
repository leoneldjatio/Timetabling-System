<?php

namespace App\Http\Controllers\General_TimeTable;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: Leonel Otun Djatio Foma
 * Date: 13/01/17
 * Time: 3:17 PM
 *
 */
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty_TimeTable\FacultyTimeTableRequest;
use App\Student;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Department;


class FacultyTimeTableController extends Controller
{
    /**
     * FacultyTimeTableController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:faculty');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadRecentTimeTable()
    {

        $allYears = Year::all()->sortByDesc('year_id');
        $year = $allYears->first();
        $sentinel = 0;
        $faculty_courses = null;
        $semester = $year->semesters()->get()->last();
        $student = Student::where('student_name', strtoupper(Auth::user()->user_name))->get()->first();
        $department = $student->departments()->get()->first();
        $faculty = Faculty::where('faculty_id', $department->faculties_faculty_id)->first();
        foreach ($faculty->departments()->get() as $department) {
            if ($sentinel == 0) {
                $faculty_courses = $department->courses()->get();
                $sentinel++;
            } else {
                $faculty_courses = $faculty_courses->merge($department->courses()->get());
            }

        }

        return view('faculty_timetable.faculty_timetable', compact('year', 'semester', 'faculty_courses','faculty', 'allYears', 'department'));
    }

    /**
     * @param FacultyTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadPastTimeTable(FacultyTimeTableRequest $request)
    {
        $allYears = Year::all()->sortByDesc('year_id');
        $keeptrack = 0;
        $year = Year::where('year_value', $request->get('year'))->get()->first();
        $semester = $year->semesters()->where('semester_name', $request->get('semester'))->get()->first();
        if ($semester == null) {
            return view('error.error1');
        }
        $student = Student::where('student_name', strtoupper(Auth::user()->user_name))->get()->first();
        $department = $student->departments()->get()->first();
        $faculty = Faculty::where('faculty_id', $department->faculties_faculty_id)->first();
        foreach ($faculty->departments()->get() as $department) {
            if ($keeptrack == 0) {
                $faculty_courses = $department->courses()->get();
                $keeptrack++;
            } else {
                $faculty_courses = $faculty_courses->merge($department->courses()->get());
            }


        }

        return view('faculty_timetable.faculty_table', compact('year', 'semester', 'faculty_courses','faculty', 'allYears', 'department'));

    }

    /**
     * @param $yearID
     * @param $semesterID
     * @param $departmentID
     * @return mixed
     */
    public function downloadPDF($yearID, $semesterID, $departmentID)
    {
        $keeptrack = 0;
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        $department = Department::where('department_id', $departmentID)->get()->first();
        $faculty = Faculty::where('faculty_id', $department->faculties_faculty_id)->first();
        foreach ($faculty->departments()->get() as $department) {
            if ($keeptrack == 0) {
                $faculty_courses = $department->courses()->get();
                $keeptrack++;
            } else {
                $faculty_courses = $faculty_courses->merge($department->courses()->get());
            }


        }
        $pdf = PDF::loadView('faculty_timetable.download_table', compact('year', 'semester','faculty', 'department', 'faculty_courses'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if($numTimeSlots >5){
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('facultytimetable.pdf');
    }
}