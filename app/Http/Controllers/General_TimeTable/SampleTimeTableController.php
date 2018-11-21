<?php

namespace App\Http\Controllers\General_TimeTable;

use App\Faculty;
use App\Http\Controllers\Controller;
use App\Student;
use App\Year;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Student_TimeTable\CompulsaryTimeTableRequest;
use App\Level;
use App\Department;
use PDF;

class SampleTimeTableController extends Controller
{
    /**
     * SampleTimeTableController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:compulsarysample');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        $allYears = Year::all()->sortByDesc('year_id');
        $year = $allYears->first();
        $semester = $year->semesters()->get()->last();
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        $faculty = Faculty::find($student->departments()->get()->first()->faculties_faculty_id);
        $levels = $this->getlevels($faculty);
        return view('sample_timetable.compulsary_timetable', compact('year', 'semester','department','levels', 'allYears'));
    }

    /**
     * @param $faculty
     * @return mixed
     */
    public function getLevels($faculty)
    {
        $sentinel = 0;
        $programs = $faculty->degreePrograms()->get();
        foreach ($programs as $program) {
            if ($sentinel == 0) {
                $levels = $program->levels()->get();
                $sentinel++;
            } else {
                $levels = $levels->concat($program->levels()->get());
            }
        }
        return $levels->unique();
    }

    /**
     * @param CompulsaryTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function compulsarySample(CompulsaryTimeTableRequest $request)
    {
         if(strcmp('First Semester',$request->get('semester'))==0){
             $semester_no = 1;
         }else{
             $semester_no=2;
         }
        $allYears = Year::all()->sortByDesc('year_id');
        $year = Year::where('year_value', $request->get('year'))->get()->first();
        $semester = $year->semesters()->where('semester_name', $request->get('semester'))->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        $level = Level::where('level_id',$request->get('level'))->get()->first();
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        $faculty = Faculty::find($department->faculties_faculty_id);
        $levels = $this->getlevels($faculty);
        $compCourses = $department->courses()->where('semester_no',$semester_no)->where('levels_level_id',$level->level_id)->get();
        return view('sample_timetable.compulsary_table', compact('year', 'semester', 'levels','allYears','compCourses','level','department'));

    }

    /**
     * @param $yearID
     * @param $semesterID
     * @param $departmentID
     * @param $levelID
     * @return mixed
     */
    public function downloadPDF($yearID,$semesterID,$departmentID,$levelID){
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        if(strcmp('First Semester',$semester->semester_name)==0){
            $semester_no = 1;
        }else{
            $semester_no=2;
        }
        $department = Department::where('department_id', $departmentID)->get()->first();
        $compCourses = $department->courses()->where('semester_no',$semester_no)->where('levels_level_id',$levelID)->get();
        $pdf = PDF::loadView('sample_timetable.download_table', compact('year','semester', 'compCourses'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if($numTimeSlots >5){
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('compulsarytimetable.pdf');
    }

}
