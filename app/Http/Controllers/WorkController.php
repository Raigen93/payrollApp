<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    //
    public function update_Hours($job_num, $hours, $department) {
        
        $currentJob = DB::table('works')->where('job_id', $job_num)->get()->first();
        $currentJob = json_decode(json_encode($currentJob, true), true);
        
        $currentHours = $currentJob['hours_worked'];
        
        $deptHours = 0;

            if ($department === "pipe" ) {
                $deptHours = $currentJob['dept_hours_pipe'];
            } else if ($department === "structural" ) {
                $deptHours = $currentJob['dept_hours_struc'];
            } else if ($department === "electrical") {
                $deptHours = $currentJob['dept_hours_elec'];
            } else if ($department === "engineering") {
                $deptHours = $currentJob['dept_hours_eng'];
            }
        $deptHours = $deptHours + $hours;
        $currentHours = $currentHours + $hours;
        $currentJob['hours_worked'] = $currentHours;
        
        if ($department === "pipe" ) {
            $currentJob['dept_hours_pipe'] = $deptHours;
        } else if ($department === "structural" ) {
            $currentJob['dept_hours_struc'] = $deptHours;
        } else if ($department === "electrical") {
            $currentJob['dept_hours_elec'] = $deptHours;
        } else if ($department === "engineering") {
            $currentJob['dept_hours_eng'] = $deptHours;
        }

            DB::table('works')->where('job_id', $job_num)->update($currentJob);
        
    }

    //create a new job in the DB
    public function make_job(Request $request) {
        $incoming_fields = $request->validate([
            "job_id" => ['required'],
            "budget_hours" => ['required'],
            "job_name" => ['required']
        ]);

        Work::create($incoming_fields);

        return redirect('/')->with('success', 'Work Order successfully created');
    }

    public function project_hours() {
        $work = DB::table('works')->paginate(3);

        return view('project_hours', ['work' => $work]);
    }

    public function search(Request $request) {
        if($request['job_name'] !== null) {
            $search = DB::table('works')->where('job_name', $request['job_name'])->paginate(1);
        } else if($request['job_id'] !== null) {
            $search = DB::table('works')->where('job_id', $request['job_id'])->paginate(1);
        } else {
            $search = DB::table('works')->paginate(3);
            return back()->with('failure', 'Name or ID required');
        }

        if($search->isEmpty()) {
            $search = DB::table('works')->paginate(3);
            return back()->with('failure', 'Unable to locate Job');
        }
        return view('project_hours', ['work'=>$search]);
        
    }
}
