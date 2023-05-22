<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\TimesheetController;


class UserController extends Controller
{
    //register a new user
    public function register(Request $request) {
        $incoming_fields = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
            'password' => ['required', 'min:10', 'confirmed'],
            'department' => ['required']
        ]);

        if($incoming_fields->fails()) {
             return back()->with('failure', 'Something went wrong, Account not created.');
        } else {
            
        //hash the password
        $incoming_fields['password'] = bcrypt($incoming_fields['password']);

        //sets username to be first 4 letters of first and last name
        $first_array = str_split($incoming_fields['first_name']);
        $last_array = str_split($incoming_fields['last_name']);

        $first_array = array_slice($first_array, 0, 4);
        $last_array = array_slice($last_array, 0, 4);

        $incoming_fields['username'] = implode($last_array) . implode($first_array);

        //create new user in the database
        $user = User::create($incoming_fields);

        return back()->with('success', 'Account successfully created');
        }
    }

    //login a user
    public function login(Request $request) {
        $incoming_fields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt([
            'username' => $incoming_fields['loginusername'], 
            'password' => $incoming_fields['loginpassword']
            ])) {
                $request->session()->regenerate();
               
                return redirect('/')->with('success', "Login Successful");
        } else {
                return redirect('/')->with('failure', "Incorrect Username or Password.");
        }
    }

    //User logout
    public function logout(Request $request) {
       auth()->logout();
      
       return redirect('/')->with('success', 'You are now logged out.');
   }

   //worked hours form
   public function worked_hours() {
    return view("worked_hours");
   }

   //submit the worked hours
   public function submit_hours(Request $request) {
        $incoming_fields = $request->validate([
            'start_time' => 'required',
            'stop_time' => 'required',
            'job_num' => 'required',
        ]);

        $user = auth()->user();
        $total_hours = $user->hours_worked;
        $last_submit = explode('T', $user->updated_at);
        $last_submit = explode(" ", $last_submit[0]);
        $today = date("Y-m-d");

        //uncomment when done Testing
      

        $start = $incoming_fields['start_time'];
        $start = explode(':', $start);
        
        $end = $incoming_fields['stop_time'];
        $end = explode(':', $end);

        $hours = $end[0] - $start[0];

        if ($hours < 0) {
            $hours = $hours + 24;
        }

        if ($hours > 5) {
            $hours = $hours - 0.5;
        }

        $minutes = $end[1] - $start[1];

        $mins_percent = $minutes / 60;

        $time_worked = $hours + $mins_percent;

        $time_worked = ceil($time_worked * 100)/100;

        $total_hours = $total_hours + $time_worked;
        
        $user->hours_worked = $total_hours;
 
        if ( DB::table('timesheets')->where('user_id', $user->id)->where('date', $today)->first() ) {
            return back()->with('failure', "Timesheet already submitted for today.");
        } else if (!DB::table('works')->where('job_id', $incoming_fields['job_num'])->first()) { 
            return back()->with('failure', "Incorrect Job number.");
        } else {
            $user->save();
            $timesheet = new TimesheetController;
            $timesheet->addTime($user, date("l"), $today, $time_worked );
            $current_job = new WorkController;
            $current_job->update_Hours($incoming_fields['job_num'], $time_worked, $user->department);
        }

        return back()->with('success', "Time of " . $time_worked . " hours successfully submitted to Job number: " . $incoming_fields['job_num']);
   }

}