<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\PTORequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeOffController extends Controller
{
    public function time_off() {
        $user = auth()->user();
        $requests = DB::table('time_off_requests')->where('user_id', $user->id)->get();


        foreach($requests as $request) {
            if($request->waiting_approval === 1) {
                $request->status = "Waiting Approval";
            } else if($request->approved === 0) {
                $request->status = "Refused";
            } else if($request->approved === 1) {
                $request->status = "Approved";
            }
        }

        return view('time_off', ['requests' => $requests]);
    }

    public function submit_time_request(Request $request) {
        $incoming_fields = $request->validate([
            "start_date" => ['required'],
            "end_date" => ['required'],
            "hours_requested" => ['required']
        ]);
        $hours_requested = $incoming_fields['hours_requested'];
        $user = auth()->user();
        $employee_pto = $user->pto_earned - $user->pto_taken;
        $start_date = new DateTime($incoming_fields['start_date']);
        $end_date = new DateTime($incoming_fields['end_date']);
        $diff = date_diff($start_date, $end_date);
        $diff = $diff->format('%a');

        if ($hours_requested > (($diff +1) * 8)) {
            return back()->with('failure', "Maximum hours requested for timeframe is " . (($diff +1) * 8));
        } else if ($employee_pto < $hours_requested) {
            return back()->with('failure', "Not enough available PTO hours.");
        }

        $pto_request = [
            'user_id'=> $user->id,
            'emp_first' => $user->first_name,
            'emp_last' => $user->last_name,
            'hours_requested' => $hours_requested,
            'leave_start' => $start_date,
            'leave_end' => $end_date,
        ];

        PTORequest::create($pto_request);
        return back()->with('success', 'Request successfully submitted.');

    }

    public function view_requests() {
        $time_requests =  DB::table('time_off_requests')->where('waiting_approval', 1)->paginate(3);
        $finalized = DB::table('time_off_requests')->where('waiting_approval', 0)->paginate(3);

        foreach ($finalized as $request) {
            if($request->approved === 0) {
                $request->status = "Refused";
            } else if($request->approved === 1) {
                $request->status = "Approved";
            }
        }
        return view('pto_requests', ['requests' => $time_requests, 'finalized' => $finalized]);
    }

    public function process_pto(Request $request) {
        $incoming_fields = $request->validate([
            'request_id' => ['required'],
            'decision' => ['required']
        ]);

        if($incoming_fields['decision'] === 'approve') {
            DB::table('time_off_requests')->where('id', $incoming_fields['request_id'])
            ->update(array('waiting_approval' => 0, 'approved' => 1));
        } else if($incoming_fields['decision'] === 'deny') {
            DB::table('time_off_requests')->where('id', $incoming_fields['request_id'])
            ->update(array('waiting_approval' => 0, 'approved' => 0));
        }

        return back()->with('success', "Time off request successfully processed.");
    }

    public function cancel_time(Request $request) {
        $incoming_fields = $request->validate([
            'request_id' => ['required']
        ]);

        DB::table('time_off_requests')->where('id', $incoming_fields['request_id'])->delete();

        return back()->with('success', "Time off request canceled.");
    }
}
