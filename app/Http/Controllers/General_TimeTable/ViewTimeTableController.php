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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\View_TimeTable\ViewTimeTableRequest;
use App\Year;
use PDF;

class ViewTimeTableController extends Controller
{
    /**
     * ViewTimeTableController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:viewtimetable');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $allYears =Year::all()->sortByDesc('year_id');
        $year=$allYears->first();
        $semester = $year->semesters()->get()->last();
        return view('view_timetable.view_timetable',compact('year','semester','allYears'));
    }

    /**
     * @param ViewTimeTableRquest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTimeTable(ViewTimeTableRequest $request)
    {
        $allYears =Year::all()->sortByDesc('year_id');
        $year = Year::where('year_value', $request->get('year'))->get()->first();
        $semester=$year->semesters()->where('semester_name', $request->get('semester'))->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        return view('view_timetable.view_table',compact('year','semester','allYears'));

    }

    /**
     * @param $yearID
     * @param $semesterID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadPDF($yearID,$semesterID){
        $year = Year::where('year_id', $yearID)->get()->first();

        $semester = $year->semesters()->where('semester_id', $semesterID)->get()->first();
        if($semester == null){
            return view('error.error1');
        }
        $pdf = PDF::loadView('view_timetable.download_table', compact('year', 'semester'));
        $numTimeSlots = $semester->weekDays()->first()->timeSlots()->get()->count();
        if($numTimeSlots >5){
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->download('generaltimetable.pdf');
    }
}