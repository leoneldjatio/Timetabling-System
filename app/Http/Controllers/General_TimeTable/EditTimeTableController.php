<?php

namespace App\Http\Controllers\General_Timetable;

/**
 * @author Go-Groups LTD
 * Created by PhpStorm.
 * User: Leonel Otun Djatio Foma
 * Date: 13/01/17
 * Time: 3:17 PM
 *
 */

use App\Allocation;
use App\Course;
use App\Department;
use App\Http\Controllers\Controller;
use App\Http\Requests\Edit_TimeTable\EditTimeTableRequest;
use App\Room;
use App\Semester;
use App\Student;
use App\Teacher;
use App\TimeSlot;
use App\Year;
use DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class EditTimeTableController extends Controller
{
    /**
     * EditTimeTableController constructor.
     */


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:edittimetable');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadRecentTimeTable()
    {
        $allYears = Year::all()->sortByDesc('year_id');
        $year = $allYears->first();
        $semester = $year->semesters()->get()->last();
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        $teacher = $department->teachers()->get()->last();
        $courses = $teacher->courses()->get();
        return view('edit_timetable.edit_timetable', compact('year', 'semester', 'department', 'allYears', 'teacher', 'courses'));
    }

    /**
     * @param EditTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadPastTimeTable(EditTimeTableRequest $request)
    {

        $allYears = Year::all()->sortByDesc('year_id');
        $year = Year::where('year_value', $request->get('year'))->get()->first();
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        $semester = $year->semesters()->where('semester_name', $request->get('semester'))->get()->first();
        if ($semester == null) {
            return view('error.error1');
        }
        $teacher = Teacher::find($request->get('teacher'));
        $depCourses = $department->courses()->get();
        $courses = $teacher->courses()->get();

        return view('edit_timetable.edit_table', compact('year', 'semester', 'teacher', 'department', 'allYears', 'courses', 'depCourses'));

    }

    /**
     * @param EditTimeTableRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function swapper(EditTimeTableRequest $request)
    {
        $tdInfo = $request->all();
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        $timeSlot = TimeSlot::where('time_slot_id', $tdInfo['timeSlotID'])->first();
        $timeSlotPopulation = $timeSlot->allocations()->get();//get a population under this timetslot
        if (($tdInfo['newCourseCode'] == "delete") && ($tdInfo['oldCourseCode'] != null)) {// a change has occured( delete course from database
            $oldCourse = $timeSlotPopulation->where('course_code', strtoupper($tdInfo['oldCourseCode']))->first();
            Allocation::destroy([$oldCourse->allocation_id]);

        } elseif (($tdInfo['newCourseCode'] != "delete") && ($tdInfo['oldCourseCode'] == null)) {// a change has occured insert course into database
            $teacher = Teacher::where('teacher_name', $tdInfo['teacherName'])->first();

            $oldCourse = Course::where('course_code', strtoupper($tdInfo['newCourseCode']))->first();

            $classroom = $this->constraintcheckerAndResolver($department, $oldCourse, $timeSlot);
                if ($classroom == null || empty($classroom)) {
                    return "RETRY AGAIN! COULD NOT FIND A ROOM";
                }
                if ($this->clashSolve($oldCourse, $timeSlotPopulation, $teacher)) {
                    DB::table('allocations')->insert([
                        'course_code' => $tdInfo['newCourseCode'],
                        'room_name' => $classroom->room_name,
                        'teacher_name' => $tdInfo['teacherName'],//global variable usage
                        'time_slots_time_slot_id' => $timeSlot->time_slot_id,
                        'departments_department_id' => $oldCourse->departments_department_id,
                        'levels_level_id' => $oldCourse->levels_level_id,
                        'course_spec' => $oldCourse->course_spec
                    ]);
                } else {
                    $message = '<span style="color:red;"><b>CLASH</b><span>';
                    return $message;
                }

        } elseif (($tdInfo['newCourseCode'] != "delete") && ($tdInfo['oldCourseCode'] != null)) {// a change has occured insertt course into database
            $oldCourse = $timeSlotPopulation->where('course_code', strtoupper($tdInfo['oldCourseCode']))->first();
           $teacher = Teacher::where('teacher_name', $tdInfo['teacherName'])->first();
            Allocation::destroy([$oldCourse->allocation_id]);
            $oldCourse = Course::where('course_code', strtoupper($tdInfo['newCourseCode']))->first();

            $classroom = $this->constraintcheckerAndResolver($department, $oldCourse, $timeSlot);
                if ($classroom == null || empty($classroom)) {
                    return 'RETRY AGAIN! COULD NOT FIND A ROOM';
                }
                if ($this->clashSolve($oldCourse, $timeSlotPopulation, $teacher)) {
                    DB::table('allocations')->insert([
                        'course_code' => $tdInfo['newCourseCode'],
                        'room_name' => $classroom->room_name,
                        'teacher_name' => $tdInfo['teacherName'],//global variable usage
                        'time_slots_time_slot_id' => $timeSlot->time_slot_id,
                        'departments_department_id' => $oldCourse->departments_department_id,
                        'levels_level_id' => $oldCourse->levels_level_id,
                        'course_spec' => $oldCourse->course_spec
                    ]);

                } else {
                    $message = '<span style="color: red;"><b>CLASH</b></span>';
                    return $message;
                }
            }

        else {
            $message = '<span style="color:orange;"><b>SELECT A COURSE TO EDIT</b></span>';
            return $message;
        }
        return $tdInfo['newCourseCode'];
    }

    /**
     * @param $department
     * @param $course
     * @param $timeSlot
     * @return mixed
     */
    public function constraintcheckerAndResolver($department, $course, $timeSlot)
    {
        $faculty = $department->faculties()->get()->first();
        $courseCapacity = $course->students()->get()->count();
        $genObject = new GenerateTimeTableController();
        $facultyClassrooms = $genObject->mergeClassrooms($faculty);
        $rooms = Room::where('room_location', $faculty->faculty_location)->get();
        $range = $rooms->min('capacity');

        $population = $timeSlot->allocations()->get(); //get the population attached to a time slot

        //solve clash by getting all rooms which are not yet allocated within the halls allocated to this faculty
        $facultyClassrooms = $facultyClassrooms->reject(function ($room) use ($population) {
            foreach ($population as $child) {
                if ($room->room_name == $child->room_name) {
                    return true;
                }
            }
        });
        //solve clash by getting all rooms which are not yet allocated within the entire campus halls
        $rooms = $rooms->reject(function ($room) use ($population) {
            foreach ($population as $child) {
                if ($room->room_name == $child->room_name) {
                    return true;
                }
            }
        });
        return $this->capacitySolver($rooms, $facultyClassrooms, $range, $courseCapacity, $course)->random();
    }

    /**
     * @param $rooms
     * @param $classrooms
     * @param $range
     * @param $courseCapacity
     * @param $course
     * @return \Illuminate\Support\Collection
     */
    public function capacitySolver($rooms, $classrooms, $range, $courseCapacity, $course)
    {

        $approximateCapacities = collect([]);
        $minCapacity = $classrooms->min('capacity');
        if ($courseCapacity < $minCapacity) {
            $approximateCapacities = $rooms->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity);
            if ($approximateCapacities->isEmpty()) {
                $approximateCapacities = $rooms->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + $range);
            }
            return $approximateCapacities->shuffle();
        }

        $approximateCapacities = $classrooms->where('room_type', $course->course_type)->where('capacity', $courseCapacity);
        if ($approximateCapacities->isEmpty()) {

            $approximateCapacities = $classrooms->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + $range);
            if ($approximateCapacities->count() == 1) {
                $approximateCapacities = $rooms->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + $range);
            }
        }
        $approximateCapacities = $approximateCapacities->shuffle();
        return $approximateCapacities;
    }

    /**
     * @param $course
     * @param $classroom
     * @param $timeSlot
     * @param $teacher
     * @return bool
     */
    public function clashSolve($course, $population, $teacher)
    {
        foreach ($population as $individualAlloc) {
            //prevent courses of same name and department from appearing in thesame time slot
            if ((strcmp($individualAlloc->course_code, $course->course_code) == 0) || (($individualAlloc->departments_department_id == $course->departments_department_id) && ($individualAlloc->levels_level_id == $course->levels_level_id))) {
                return false;
            }
            if ((strcmp($individualAlloc->teacher_name, $teacher->teacher_name) == 0)) {//status 2 will be read from the configuration table
                return false;
            }
            if (!$this->solveCrossOver($course, $individualAlloc)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $course
     * @param $alloc
     * @return bool
     */
    public function solveCrossOver($course, $alloc)
    {
        $students = Course::where('course_code', $alloc->course_code)->first()->students()->get();
        foreach ($course->students()->get() as $student) {
            foreach ($students as $stud) {
                if ((($student->student_name == $stud->student_name) && ($student->student_id == $stud->student_id))) {
                    return false;
                }
            }
        }
        return true;
    }

    public function loadValidCourses(EditTimeTableRequest $request)
    {
        $tdInfo = $request->all();
        $semester = Semester::where('semester_name', $tdInfo['Semester'])->first();
        if($semester->semester_name == 'First Semester'){
            $semester_no = 1;
        }else{
            $semester_no=2;
        }
        $student = Student::where('student_name', Auth::user()->user_name)->get()->first();
        $department = $student->departments()->get()->first();
        $timeSlot = TimeSlot::where('time_slot_id', $tdInfo['timeSlotID'])->first();
        $timeSlotPopulation = $timeSlot->allocations()->where('departments_department_id', $department->department_id)->get();
        $teacher = Teacher::where('teacher_name', $tdInfo['teacherName'])->first();
        if($tdInfo['oldCourseCode'] == null) {
            $lectCourses = $teacher->courses()->where('departments_department_id', $department->department_id)->where('semester_no', $semester_no)->get();
            $numberOfCourses = $teacher->courses()->where('departments_department_id', $department->department_id)->where('semester_no', $semester_no)->count();
            $lectCourses = $lectCourses->reject(function ($lectcourse) use ($timeSlotPopulation) {
                foreach ($timeSlotPopulation as $allocation) {
                    if ($allocation->levels_level_id == $lectcourse->levels_level_id) {
                        return true;
                    }
                }
            });
        }else{
            $lectCourses = Collect([]);
        }
        $oldCourse = $tdInfo['oldCourseCode'];
        $teacherName = $tdInfo['teacherName'];
        if(($oldCourse == null && $lectCourses->isEmpty())) {
            $message = '<span style="color: red;"><b>POSSIBLE CLASH</b></span>';
            return $message;
        }

        else {
            return view('edit_timetable.editable_options', compact('lectCourses', 'timeSlot', 'teacherName', 'teacher', 'oldCourse','numberOfCourses'));
        }
    }

    /**
     * @param $yearID
     * @param $semesterID
     * @param $teacherID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadPDF($yearID, $semesterID, $departmentID, $teacherID)
    {
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        $department = Department::where('department_id', $departmentID)->get()->first();
        $teacher = Teacher::where('teacher_id', $teacherID)->get()->first();
        if ($semester == null) {
            return view('error.error1');
        }
        $courses = $teacher->courses()->get();
        $pdf = PDF::loadView('edit_timetable.download_table', compact('year', 'semester', 'department', 'teacher', 'courses'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if ($numTimeSlots > 5) {
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('editedtimetable.pdf');
    }
}