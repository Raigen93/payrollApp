<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Weekly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeeklyController extends Controller
{
    public function submitWeek() {
        $users = DB::table('users')->pluck('id');
        $users = json_decode(json_encode($users, true), true);
    
        foreach($users as $user) {
        $user_info = DB::table('users')->where('id', $user)->get();
        $user_info_arr = json_decode(json_encode($user_info[0], true), true);
        $timesheets = DB::table('timesheets')->where('user_id', $user_info['id'])->pluck('hours_worked', 'weekday');
        $timesheets = json_decode(json_encode($timesheets, true), true);
        
        $firstDay = DB::table('timesheets')->where('user_id', $user_info['id'])->pluck('date', 'weekday');

        $total_hours = 0;
        foreach($timesheets as $time) {
            $total_hours = $total_hours + $time;
        }

        $gross = $user->wage * $total_hours;
        $tax = $gross * 0.07;
        $net = $gross - $tax;
        
        $work_week = [
            'user_id'=> $user_info_arr['id'],
            'employee_first' => $user_info_arr['first_name'],
            'employee_last' => $user_info_arr['last_name'],
            'total_hours' => $total_hours,
            'gross' => $gross,
            'net' => $net,
            'tax' => $tax
        ];

        $work_week = array_merge($work_week, $timesheets );

        increase_pto_hours($user_info, $total_hours);

        Weekly::create($work_week);
        DB::table('timesheets')->where('user_id', $user_info['id'])->delete();
        }
 
    }

    public function getWeek() {
        $user = auth()->user();
       
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);

        $current_week = DB::table('weeklies')->where('user_id', $user->id)->whereBetween('created_at', [$start_week, $end_week])->get();
        $current_week = json_encode($current_week, true);

        return $current_week;
    }

    public function getAll() {
        $user = auth()->user();
        $week_times = DB::table('weeklies')->where('user_id', $user->id)->get();
        $week_arr = json_decode(json_encode($week_times, true), true);

        return view('paystub', ['stubs' =>  $week_times, 'weeks' => $week_arr]);
    }

    public function increase_pto_hours(User $user, $total_hours) {
        $new_pto_time = $user->pto_earned + ($total_hours/20);

        DB::table('users')->where('id', $user->id)->update('pto_earned', $new_pto_time);
    }

}
