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

use App\Configuration;
use App\Course;
use App\Day;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\General_TimeTable\GenerateTimeTableRequest;
use App\Level;
use App\Notifications\NotifyStudent;
use App\Room;
use App\Semester;
use App\Student;
use App\Teacher;
use App\WeekDay;
use App\Year;
use DB;
use Illuminate\Support\Facades\Notification;
use Mockery\CountValidator\Exception;
use PDF;

/**
 * ODD and Even constant definitions
 */
define('ODD', 5, true);
define('EVEN', 2, true);

class GenerateTimeTableController extends Controller
{
    /**
     * get the unique teacher that will avoid clashes in the general timetable
     * @var
     */

    public $teacher, $groupsCount;

    /**
     * GenerateTimeTableController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:generate');
    }

    /**
     * Return the view for generation of general timetable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create()
    {
        $date = date('Y');
        $current_year = collect();
        for ($i = $date; $i < (5 + $date); $i++) {
            $current_year = $current_year->merge($i . '/' . ($i + 1));
        }
        $allYears = Year::all();
        return view('general_timetable.general_timetable', compact('current_year', 'allYears'));
    }


    /************************************************************************
     * The following lines of code are functions that                       *
     *  actually partake in the construction  and generation                *
     * for the general timetable of universities                            *
     ************************************************************************/


    public function generate(GenerateTimeTableRequest $request)
    {
        $current_semester = $request->get('semester');
        $current_year = $request->get('year');
        //return an error messages if the chromosome for this year already exist in the database
        if ($this->chromosomeExist($current_year, $current_semester)) {
            return view('error.error');
        }

        //represent the data of this year in a chromsomes format
        $year = $this->chromosomeRepresentation($current_semester, $current_year);
        if ($year == null) {
            return redirect()->action('ConfigurationController@create');
        }


        $semester = $year->semesters()->get()->last();
        $faculties = Faculty::all();
        //set the maximum execution time of php to 3 minitues
        ini_set('max_execution_time', 5000);
        ///this function does all allocations
        $this->populate($semester, $faculties);
        //reset the status of all courses to ease next generation
        $this->reInitialize($semester);
        //finalizes the t  generation by sending text messages to students and lecturers
        //$this->finalize($semester);

        return view('general_timetable.general_table', compact('year', 'semester'));
    }

    /**
     * Checks if the chromosome for the current year already exist
     * @param $year
     * @return bool
     */
    public function chromosomeExist($year, $current_semester)
    {
        // check if chromosome already exist in the database

        if (Year::where('year_value', $year)->exists()) {
            $year = Year::where('year_value', $year)->get()->last();
            $semesters = $year->semesters()->get();
            if ($semesters->count() == 2) {
                return true;
            }
            foreach ($semesters as $semester) {
                if (strcmp($semester->semester_name, $current_semester) == 0) {
                    return true;
                }
            }

        } else {
            DB::table('years')->insert(['year_value' => $year]);
        }
        return false;
    }

    /**
     * @param GenerateTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //not yet completed
    /**
     * @param $current_semester
     * @param $current_year
     * @return mixed
     */

    public function chromosomeRepresentation($current_semester, $current_year)
    {
        /***************************************************************************
         * array declarations for the days of the week and the different timeslots *
         *  in which lectures holds during the week                                *
         ***************************************************************************/

        //week days in which lectures are being taught in the university of buea
        //to be read from the configuration table

        $week_days = Day::all();

        $configurations = Configuration::all();
        if ($week_days->isEmpty() || $configurations->isEmpty()) {
            return null;
        }
        $configurations = Configuration::all()->last();
        //creates an entry for the current year and semester accordingly fulfilling all constraints

        $year = Year::where('year_value', $current_year)->first();
        $semester = Semester::find(DB::table('semesters')->insertGetId(['semester_name' => $current_semester, 'years_year_id' => $year->year_id]));
        //get the maximun period duration per day and the start time inorder to generate the different time slots
        $period_duration = '+' . $configurations->period_duration . 'minutes';
        $timeslot = date('h:iA', strtotime($configurations->start_time));

        //loops creating a chromosome repressentation of the timetable into the database
        foreach ($week_days as $week_day) {
               $day = WeekDay::find(DB::table('week_days')->insertGetId(['week_day_name' => $week_day->day_name, 'semesters_semester_id' => $semester->semester_id]));

            for ($period = 0; $period < $configurations->max_day_period; $period++) {
                //generate the diffferent time slots
                $next_period = date('h:iA', strtotime($period_duration, strtotime($timeslot)));
                DB::table('time_slots')->insert(['time_slot_name' => $timeslot . '   -   ' . $next_period, 'week_days_week_day_id' => $day->week_day_id]);
                $timeslot = $next_period;
            }
        }
        return $year;
    }

    /**
     *  Does all allocations storing clashes in the clash table
     * @param $semester
     * @param $faculties
     */


    public function populate($semester, $faculties)
    {

        try {
            $maxLPeriod = Configuration::all()->last()->lecturer_max_day_period;
            $maxPDuration = Configuration::all()->last()->period_duration / 60;
            $should_split = Configuration::all()->last()->should_split;
            $loopSentinel = random_int(1, 10);
            $fitness = 0;
            if(strcmp($semester->semester_name,'First Semester')==0){
                $semester_no = 1;
            }else{
                $semester_no=2;
            }
            for (; ;) {
                $weekDaySentinel = $loopSentinel;
                foreach ($semester->weekDays()->get() as $weekDay) {
                    $timeslotSentinel = $weekDaySentinel;
                    foreach ($weekDay->timeSlots()->get() as $timeSlot) {
                        $facultySentinel = $timeslotSentinel;
                        foreach ($faculties as $faculty) {
                            $facultyCourses = $this->mergeCourses($faculty, $semester, $maxPDuration, $facultySentinel, $fitness);
                            $facultyClassrooms = $this->mergeClassrooms($faculty);
                            $range = $facultyClassrooms->min('capacity');
                            foreach ($facultyCourses as $facultyCourse) {
                                $handled = false;
                                $courseCapacity = $facultyCourse->students()->get()->count();
                                $teachers = $facultyCourse->teachers()->where('teacher_status', '<', $maxLPeriod)->get();
                                if ($this->isException(Room::where('room_location', $faculty->faculty_location), $courseCapacity, $facultyCourse)) {
                                    if (strcmp(strtolower($should_split), 'true') == 0) {
                                        $handled = $this->superExceptionHandler($faculty, $facultyCourse, $courseCapacity, $semester, $teachers, $should_split);
                                    } else {
                                        $approximateCapacities = $this->superExceptionHandler($faculty, $facultyCourse, $courseCapacity, $semester, $teachers, $should_split);
                                    }
                                } else if ($this->isException($facultyClassrooms, $courseCapacity, $facultyCourse)) {
                                    $approximateCapacities = $this->classroomException($range, $faculty, $courseCapacity, $facultyCourse);
                                } else {
                                    $approximateCapacities = $this->solveSpaceConstraint($facultyClassrooms, $faculty, $range, $courseCapacity, $facultyCourse);
                                }
                                if ($teachers->isEmpty()) {
                                    ;
                                } elseif ($teachers == null) {
                                    ;
                                } else {
                                    if (!$handled) {
                                        foreach ($approximateCapacities as $approximateCapacity) {
                                            if ($this->clashFree($facultyCourse, $approximateCapacity, $timeSlot, $teachers)) {
                                                //delete the allocated one here later
                                                DB::table('allocations')->insert([
                                                    'course_code' => $facultyCourse->course_code,
                                                    'room_name' => $approximateCapacity->room_name,
                                                    'teacher_name' => $this->teacher->teacher_name,//global variable usage
                                                    'time_slots_time_slot_id' => $timeSlot->time_slot_id,
                                                    'departments_department_id' => $facultyCourse->departments_department_id,
                                                    'levels_level_id' => $facultyCourse->levels_level_id,
                                                    'course_spec' => $facultyCourse->course_spec
                                                ]);
                                                $facultyCourse->course_status += 1;
                                                $facultyCourse->save();
                                                $this->teacher->teacher_status += 1;
                                                $this->teacher->save();
                                                break;
                                            }
                                        }

                                    }
                                }
                            }
                            //enables creation of allocations in the form of chess board
                            if ($this->isEven($facultySentinel)) {
                                $facultySentinel = ODD;
                            } else {
                                $facultySentinel = EVEN;
                            }
                        }
                        //enables creation of allocations in the form of chess board
                        $this->optimize();
                        if ($this->isEven($timeslotSentinel)) {
                            $timeslotSentinel = ODD;
                        } else {
                            $timeslotSentinel = EVEN;
                        }
                    }

                    /* **********************************************************************************************
                     *  reset the status of the teacher to zero to allow allocation for the next week day go well   *
                     *  based on soft constraint for each teacher                                                           *
                     *                                                                                              *
                     ************************************************************************************************/
                    $this->resetTeacherStatus();
                    //enables creation of allocations in the form of chess board
                    if ($this->isEven($weekDaySentinel)) {
                        $weekDaySentinel = ODD;
                    } else {
                        $weekDaySentinel = EVEN;
                    }

                }
                $allCourses = Course::where('semester_no',$semester_no)->get();
                $allCourses = $allCourses->reject(function ($course) use ($maxPDuration) {

                    return ($course->credit_value <= ($course->course_status * $maxPDuration));
                })->map(function ($course) {
                    return $course;
                });
                if ($allCourses->count() == 0 || $fitness == 3) {
                    break;
                }
                if ($this->isEven($loopSentinel)) {
                    $loopSentinel = ODD;
                } else {
                    $loopSentinel = EVEN;
                }
              if($fitness == 3){

                  break;
              }
                $fitness++;
            }
        } catch (Exception $e) {
            //to be continued
        }

    }

    /**
     * @param $faculty
     * @param $semester
     * @return null
     */
    public function mergeCourses($faculty, $semester, $pduration, $timeslotSentinel, $fitness)
    {
        $facultyCourses = collect([]);
        $sentinel = 0;
        if (strcmp($semester->semester_name, 'First Semester') == 0) {
            $semester_no = 1;
        } else {
            $semester_no = 2;
        }

        //get all departments of this faculty to merge their courses
        $departments = $faculty->departments()->get();
        foreach ($departments as $department) {
            if ($sentinel == 0) {

                //reject all courses whose total number of hourse
                $facultyCourses = $department->courses()->get()->where('semester_no', $semester_no);
                $sentinel++;
            } else {
                //reject all courses whose total number of hourse

                $facultyCourses = $facultyCourses->concat($department->courses()->get()->where('semester_no', $semester_no));
            }
        }
        //rejects all courses whose full teaching hours are completed
        $facultyCourses = $facultyCourses->reject(function ($course) use ($pduration) {

            return ($course->credit_value <= ($course->course_status * $pduration));
        })->map(function ($course) {
            return $course;
        });
        //if ($fitness == 0) {
            //prevents students to have straight forward courses
            if ($this->isEven($timeslotSentinel)) {
                $facultyCourses = $facultyCourses->reject(function ($course) use ($timeslotSentinel) {
                    $courseLevel = Level::find($course->levels_level_id);
                    $levelFirstDigit = $this->stripFirstDigit((int)$courseLevel->level_name);
                    if (!$this->isEven($levelFirstDigit)) {
                        return true;
                    } else {
                        return false;
                    }
                })->map(function ($course) {
                    return $course;
                });
            } else {
                $facultyCourses = $facultyCourses->reject(function ($course) use ($timeslotSentinel) {
                    $courseLevel = Level::find($course->levels_level_id);
                    $levelFirstDigit = $this->stripFirstDigit((int)$courseLevel->level_name);
                    if ($this->isEven($levelFirstDigit)) {
                        return true;
                    } else {
                        return false;
                    }
                })->map(function ($course) {
                    return $course;
                });
            }
        //}

        //shuffle the courses to make allocations random

        $facultyCourses = $facultyCourses->shuffle();
        $facultyCourses = $facultyCourses->sortBy('course_status');
        return $facultyCourses->values()->all();
    }

    /**
     * @param $period
     * @return bool
     */
    public function isEven($period)
    {
        if ($period % 2 == 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $levelNAME
     * @return int
     */
    public function stripFirstDigit($levelNAME)
    {
        return (int)floor($levelNAME / 100);
    }

    /**
     * @param $faculty
     * @return null
     */
    public function mergeClassrooms($faculty)
    {
        $allClassrooms = collect([]);
        $sentinel = 0;

        foreach ($faculty->degreePrograms()->get() as $degreeProgram) {
            foreach ($degreeProgram->buildings()->get() as $building) {
                if ($sentinel == 0) {
                    $allClassrooms = $building->rooms()->get();
                    $sentinel++;
                } else {
                    $allClassrooms = $allClassrooms->merge($building->rooms()->get());
                }

            }
        }
        $allClassrooms = $allClassrooms->unique();
        return $allClassrooms->shuffle();
    }

    /**
     * @param $course
     * @param $classrooms
     * @return bool
     */
    public function isException($classrooms, $courseCapacity, $course)
    {
        $maxroomCapacity = $classrooms->where('room_type', $course->course_type)->max('capacity');
        if ($courseCapacity > $maxroomCapacity) {
            return true;
        }
        return false;
    }

    /**
     * @param $faculty
     * @param $course
     * @param $capacity
     * @param $semester
     * @param $teachers
     * @param $should_split
     * @return bool
     */
    public function superExceptionHandler($faculty, $course, $capacity, $semester, $teachers, $should_split)
    {
        $rooms = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->get();
        $maxCapacity = $rooms->max('capacity');
        $numOfAllocs = $capacity / $maxCapacity;
        if (strcmp(strtolower($should_split), 'true') == 0) {
            $this->groupFormation($faculty, $course, $semester, $teachers, $numOfAllocs, $rooms, $maxCapacity, $capacity);
            return true;
        } else {
            return $rooms->where('capacity', $maxCapacity);
        }
    }

    /**
     * @param $approximateCapacities
     * @param $course
     * @param $slots
     * @param $teachers
     * @param $numOfAllocs
     * @return int
     */
    public function groupFormation($faculty, $course, $semester, $teachers, $numOfAllocs, $rooms, $maxCapacity, $capacity)
    {
        if ($teachers->isEmpty()) {
            ;
        } elseif ($teachers == null) {
            ;
        } else {
            // to be visisted so as to as the string including group name e.g Group A Group B ... in the DB creating method for course code
            $i = 0;
            $tracker = 1;
            $groups = Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $range = $rooms->min('capacity');
            $weekDays = $semester->weekDays()->get();
            $numOfWD = $weekDays->count();
            $classes = $rooms;
            if (ceil($numOfAllocs) < $numOfWD) {
                $weekDays = $weekDays->random(ceil($numOfAllocs));
                foreach ($weekDays as $weekDay) {
                    $timeslot = $weekDay->timeSlots()->get()->random();
                    $population = $timeslot->allocations()->get(); //get the population attached to a time slot
                    //solve clash by getting all rooms which are not yet allocated within the entire campus halls
                    $rooms = $rooms->reject(function ($room) use ($population) {
                        foreach ($population as $child) {
                            if ($room->room_name == $child->room_name) {
                                return true;
                            }
                        }
                    });
                    if ($tracker == ceil($numOfAllocs)) {
                        $remCapacity = $capacity - ($maxCapacity * floor($numOfAllocs));
                        if ($remCapacity != 0)
                            $maxCapacity = $remCapacity;
                    }

                    $classrooms = $rooms->where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', $maxCapacity);
                    if ($classrooms->isEmpty()) {
                        $classrooms = $rooms->where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $maxCapacity)->where('capacity', '<=', ($maxCapacity + $range));

                        if ($classrooms->isEmpty()) {
                            $classrooms = $rooms->where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $maxCapacity);
                        }
                    }
                    foreach ($classrooms as $classroom) {
                        if ($this->clashFree($course, $classroom, $timeslot, $teachers)) {
                            //delete the allocated one here later
                            DB::table('allocations')->insert([
                                'course_code' => $course->course_code,
                                'room_name' => $classrooms->random()->room_name . '  GROUP ' . $groups[$i++] . ' ',
                                'teacher_name' => $this->teacher->teacher_name,
                                'time_slots_time_slot_id' => $timeslot->time_slot_id,
                                'departments_department_id' => $course->departments_department_id,
                                'levels_level_id' => $course->levels_level_id,
                                'course_spec' => $course->course_spec
                            ]);
                            $this->teacher->teacher_status += 1;
                            $this->teacher->save();
                            break;
                        }
                    }
                    $rooms = $classes;
                    $tracker++;
                }
            } else {
                $loopNumber = ceil(ceil($numOfAllocs) / $numOfWD);
                for ($i = 0; $i < $loopNumber; $i++) {
                    $weekDays = $weekDays->random($numOfWD);
                    foreach ($weekDays as $weekDay) {
                        $timeslot = $weekDay->timeSlots()->get()->random();
                        $population = $timeslot->allocations()->get(); //get the population attached to a time slot
                        //solve clash by getting all rooms which are not yet allocated within the entire campus halls
                        $rooms = $rooms->reject(function ($room) use ($population) {
                            foreach ($population as $child) {
                                if ($room->room_name == $child->room_name) {
                                    return true;
                                }
                            }
                        });
                        if ($tracker == ceil($numOfAllocs)) {
                            $remCapacity = $capacity - ($maxCapacity * floor($numOfAllocs));
                            if ($remCapacity != 0)
                                $maxCapacity = $remCapacity;
                        }

                        $classrooms = $rooms->where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', $maxCapacity);
                        if ($classrooms->isEmpty()) {
                            $classrooms = $rooms->where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $maxCapacity)->where('capacity', '<=', ($maxCapacity + $range));

                            if ($classrooms->isEmpty()) {
                                $classrooms = $rooms->where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $maxCapacity);
                            }
                        }
                        foreach ($classrooms as $classroom) {
                            if ($this->clashFree($course, $classroom, $timeslot, $teachers)) {
                                //delete the allocated one here later
                                DB::table('allocations')->insert([
                                    'course_code' => $course->course_code,
                                    'room_name' => $classrooms->random()->room_name . '  GROUP ' . $groups[$i++] . ' ',
                                    'teacher_name' => $this->teacher->teacher_name,
                                    'time_slots_time_slot_id' => $timeslot->time_slot_id,
                                    'departments_department_id' => $course->departments_department_id,
                                    'levels_level_id' => $course->levels_level_id,
                                    'course_spec' => $course->course_spec
                                ]);
                                $this->teacher->teacher_status += 1;
                                $this->teacher->save();
                                break;
                            }
                        }
                        $rooms = $classes;
                        $tracker++;
                    }
                }
            }
            $course->course_status += 1;
            $course->save();
        }
    }

    /**
     * check if there is a clash for the present allocation to be done
     * @param $course
     * @param $classroom
     * @param $timeSlote
     * @return bool
     */
    public function clashFree($course, $classroom, $timeSlot, $teachers)
    {
        $population = $timeSlot->allocations()->get();
        if ($population->isEmpty()) {
            $this->teacher = $teachers->first();
            return true;
        }

        if ($teachers->isEmpty()) {
            $this->teacher = Teacher::all()->first();
            $this->teacher->teacher_name = 'No teacher';
            return true;
        }

        foreach ($population as $individualAlloc) {

            //prevent courses of same name and department from appearing in thesame time slot
            if ((strcmp($individualAlloc->course_code, $course->course_code) == 0) || (strcmp($individualAlloc->room_name, $classroom->room_name) == 0) || (($individualAlloc->departments_department_id == $course->departments_department_id) && ($individualAlloc->levels_level_id == $course->levels_level_id))) {
                return false;
            }

            //removes  lecturer clashes
            if ($teachers->count() == 1) {
                $teacher = $teachers->first();
                if ((strcmp($individualAlloc->teacher_name, $teacher->teacher_name) == 0)) {//status 2 will be read from the configuration table
                    return false;
                }

            } else {
                foreach ($teachers as $teacher) {
                    if ((strcmp($individualAlloc->teacher_name, $teacher->teacher_name) == 0)) {
                        $teacher->alloc_exist = 1;
                        $teacher->save();
                    }
                }
            }
            if (!$this->solveCrossOver($course, $individualAlloc)) {
                return false;
            }
        }
        if ($teachers->count() == $teachers->where('alloc_exist', 1)->count()) {
            return false;
        } else {

            $this->teacher = $teachers->where('alloc_exist', 0)->shuffle()->first();
            return true;
        }
    }

    /**
     * solve the crossover problem
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

    /**
     * @param $course
     * @param $range
     * @return mixed
     */
    public function classroomException($range, $faculty, $courseCapacity, $course)
    {
        $approximateCapacities = collect([]);
        $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', $courseCapacity)->get();
        if ($approximateCapacities->isEmpty()) {
            $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + $range)->get();
            if ($approximateCapacities->isEmpty()) {
                $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->get();
            }
        }

        $approximateCapacities = $approximateCapacities->shuffle();
        return $approximateCapacities;
    }

    /**
     * Solves space constraints beteween course size and classrooms
     * @param $course
     * @param $classrooms
     * @param $range
     * @return mixed
     */
    public function solveSpaceConstraint($classrooms, $faculty, $range, $courseCapacity, $course)
    {
        $approximateCapacities = collect([]);
        $minCapacity = $classrooms->min('capacity');
        if ($courseCapacity < $minCapacity) {
            $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $minCapacity)->get();
            if ($approximateCapacities->isEmpty()) {
                $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + (Room::where('room_location', $faculty->faculty_location)->min('capacity')))->get();
                if ($approximateCapacities->isEmpty()) {
                    $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->get();
                }
            }
            return $approximateCapacities->shuffle();
        }

        $approximateCapacities = $classrooms->where('room_type', $course->course_type)->where('capacity', $courseCapacity);
        if ($approximateCapacities->isEmpty()) {

            $approximateCapacities = $classrooms->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + $range);
            if ($approximateCapacities->isEmpty()) {
                $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->get();
            }
            if ($approximateCapacities->count() == 1) {
                $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity)->where('capacity', '<=', $courseCapacity + $range)->get();
                if ($approximateCapacities->isEmpty()) {
                    $approximateCapacities = Room::where('room_location', $faculty->faculty_location)->where('room_type', $course->course_type)->where('capacity', '>', $courseCapacity);
                }
            }
        }

        $approximateCapacities = $approximateCapacities->shuffle();
        return $approximateCapacities;
    }

    /**
     * reset the status of the alloc_exist attribute to permit reallocation in the
     * next timeslot
     *
     */
    public function optimize()
    {
        $allTeachers = Teacher::where('teacher_status', '>', 0)->get();
        foreach ($allTeachers as $teacher) {
            $teacher->alloc_exist = 0;
            $teacher->save();
        }
    }

    /**
     * reset the status of the teacher for a day to allow allocation for the next day
     * after making a teacher to have a precised number of courses taught for a day
     */
    public function resetTeacherStatus()
    {
        $allTeachers = Teacher::where('teacher_status', '>', 0)->get();
        foreach ($allTeachers as $teacher) {
            $teacher->teacher_status = 0;
            $teacher->save();
        }
    }

    /**
     * reset the status of all courses back to zero for the next timetable
     * generation
     */
    public function reInitialize($semester)
    {
        if(strcmp($semester->semester_name,'First Semester')==0){
          $semester_no = 1;
        }else{
            $semester_no=2;
        }

        //get all courses and lectuers
        $allCourses = Course::where('semester_no',$semester_no)->get();

        $allTeachers = Teacher::where('teacher_status', '>', 0)->get();
        // reset the course status for all courses to zero for next timetable generation
        foreach ($allCourses as $course) {
            $course->course_status = 0;
            $course->save();
        }
        /*
         * set the attribute allocExist for all teachers to zero such that other lectuers for thesame
         * course can be allocated should incase one of them is already allocated for a timeslot
         *
         */

        foreach ($allTeachers as $teacher) {
            $teacher->alloc_exist = 0;
            $teacher->save();
        }

        return 0;
    }

    /**
     * @param GenerateTimeTableRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintain(GenerateTimeTableRequest $request)
    {
        $current_year = $request->get('year');
        $current_semester = $request->get('semester');
        $year = Year::where('year_value', $current_year)->first();
        $semester = $year->semesters()->where('semester_name', $current_semester)->get()->first();
        $this->finalize($semester);
        return view('general_timetable.general_table', compact('year', 'semester'));

    }

    /**
     * allocates clash classes from the clash table into the timetable thereby creating a final timetable
     * @param $semester
     * @return int
     */
    public function finalize($semester)
    {


        //notifies all lecturers and students to check their timetable after generation
        $students = Student::where('student_type', 'undergraduate')->get();
        $teachers = Teacher::where('teacher_type', 'full time')->get();

        $message = 'Hello There! You can now Access Your ' . $semester->semester_name . ' ' . 'Time Table For This Academic Year on your Go-Student Account';
        try {
            Notification::send($students, new NotifyStudent($message));
            Notification::send($teachers, new NotifyStudent($message));
        } catch (\Nexmo\Client\Exception\Request $e) {
            //PSR-7 Response Message
            $response = $e->getEntity()->getResponse();
            return $response;
        }
        return 0;
    }

    /**
     * @param $yearID
     * @param $semesterID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function downloadPDF()
    {
        $year = Year::all()->last();

        $semester = $year->semesters()->get()->last();
        if ($semester == null) {
            return view('error.error1');
        }
        $pdf = PDF::loadView('general_timetable.download_table', compact('year', 'semester'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if ($numTimeSlots > 5) {
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('generaltimetable.pdf');
    }


}
