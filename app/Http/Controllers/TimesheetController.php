<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimesheetController extends Controller
{
    public function addTime($user, $weekday, $date, $hours_worked) {
        
        $timesheet = [
            'user_id' => $user->id,
            'employee_first' => $user->first_name,
            'employee_last' => $user->last_name,
            'weekday' => $weekday,
            'date' => $date,
            'hours_worked' => $hours_worked
        ];
    
    Timesheet::create($timesheet);  
    
}

    public function getTimes() {
        $user = auth()->user();
        $timesheets = DB::table('timesheets')->where('user_id', $user->id)->get();
        $timesheets = collect($timesheets)->sortBy('date')->all();

        return $timesheets;
    }

    public function getThisWeek() {
        $user = auth()->user();
        $timesheets = DB::table('timesheets')->where('user_id', $user->id)->get();
        $timesheets = json_decode(json_encode($timesheets, true), true);
        
        $this_week = [
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0
        ];

        foreach($timesheets as $time) {
            $day = $time['weekday'];
            $this_week[$day] = $time['hours_worked'];
        }

        return $this_week;
    }

    public function pto_timesheet() {
        $users = DB::table('users')->pluck('id');

        /*
        Take each user, and each of their time off requests. Then, check if any of the dates (start-finish) are today.
        If so, add hours to the user's pto_taken, and create a timesheet charged to Time Off work order.

        It will need to account for the total pto hours requested, and for multiple days off,
        */

        foreach ($users as $user) {
            $requests = DB::table('time_off_requests')->where('user_id', $user)->where('approved', 1)->get();

            foreach ($requests as $request) {
                $employee = DB::table('users')->where('id', $user)->first();
                
                $date_arr = [];

                $start_date = new DateTime($request->leave_start);
                $end_date = new DateTime($request->leave_end);
                $diff = date_diff($start_date, $end_date);
                $diff = $diff->format('%a');

                $time_off_hours = $request->hours_requested;
                
                $day_off= $request->leave_start;
                
                if ($diff === 0 ) {
                    array_push($date_arr, $day_off);
                } else if( $diff > 0) {
                    
                    for($x = 0; $x <= $diff; $x++) {

                        $day_off = date('Y-m-d', strtotime($request->leave_start. " +$x days"));
                        
                        if (date('N', strtotime($day_off)) >= 6) {
                            continue;
                            } else {
                                array_push($date_arr, $day_off);
                            }
                    }

                }

                $start_week = date("Y-m-d", strtotime('sunday last week'));
                $end_week = date("Y-m-d", strtotime('sunday this week'));

                $timesheet_arr = [];
                $num_days = count($date_arr);
                $max = 8;
                $hours_facade = $time_off_hours;
                
                foreach($date_arr as $date) {
                    if ($date > $start_week && $date < $end_week) {
                        $hours = 0;

                        if ($num_days === 1) {
                            $hours = $hours_facade;
                        } else {
                            if ($time_off_hours >= $max) {
                                $hours = $max;
                                $hours_facade = $hours_facade - $max;
                            } else if ($hours_facade < $max) {
                                $hours = $hours_facade;
                            }

                        }

                        $timesheet = [
                            'user_id' => $employee->id,
                            'employee_first' => $employee->first_name,
                            'employee_last' => $employee->last_name,
                            'weekday' => date('l', strtotime($date)),
                            'date' => $date,
                            'hours_worked' => $hours
                        ];

                        array_push($timesheet_arr, $timesheet);
                        
                    }
                } 
                
                foreach($timesheet_arr as $timesheet) {
                 Timesheet::create($timesheet);
                }
                 
                $pto_taken = $employee->pto_taken + $time_off_hours;
                DB::table('users')->where('id', $employee->id)->update(array('pto_taken' => $pto_taken));
                
            }
        }
    } 
}
