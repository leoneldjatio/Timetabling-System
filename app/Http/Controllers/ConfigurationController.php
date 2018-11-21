<?php

namespace App\Http\Controllers;

/**
 * Go-groups Ltd
 * User:Leonel Foma
 * Class configurationController
 * @package App\Http\Controllers
 */

use App\Year;
use Illuminate\Http\Request;
use App\Configuration;
use App\Day;
use App\Http\Requests\ConfigurationRequest;
use DB;
use RealRashid\SweetAlert\Facades\Alert;
use PhpParser\Node\Expr\Array_;

class ConfigurationController extends Controller
{
    /**
     * ConfigurationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:generate');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $week_days = array(
            'Monday', 'Tuesday', 'Wednesday',
            'Thursday', 'Friday', 'Saturday',
            'Sunday');

        return view('general_timetable.configuration', compact('week_days'));
    }

    /**
     * @param ConfigurationRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(ConfigurationRequest $request)
    {
        if (Day::all()->isNotEmpty()) {
            $days = Day::all();
            $day_ids=$days->map(function($day){
                return $day->day_id;
            });
            Day::destroy($day_ids->toArray());//flush the table for next input
        }
        if(Configuration::all()->isNotEmpty()){
            $configurations = Configuration::all();
            $config_ids=$configurations->map(function($config){
                return $config->configuration_id;
            });
            Configuration::destroy($config_ids->toArray());//flush the table for next input
        }
        $weekdays = $request->get('weekdays') ;
        //input new days configuration
        foreach ($weekdays as $weekday) {
            DB::table('days')->insert(['day_name' => $weekday]);
        }

        DB::table('configurations')->insert([
           'max_day_period' => abs($request->get('dperiods'))>1 ? abs($request->get('dperiods')) : 5,
           'lecturer_max_day_period' => abs($request->get('lperiods'))>0 ? abs($request->get('lperiods')) : 3,
            'period_duration' => abs($request->get('pduration')) >= 15 ?  abs($request->get('pduration')) : 60,
            'start_time' => $request->get('start_time'),
            'should_split' => $request->get('should_split'),
        ]);
        $config = Configuration::all()->last();

        return view('general_timetable.configuration_table',compact('config','weekdays'));

    }
}
