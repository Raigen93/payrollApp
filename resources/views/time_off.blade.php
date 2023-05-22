<x-layout>

    @if ( !$requests->isEmpty() )
        <div>
        <h4> Submitted Requests </h4>
        @foreach ($requests as $requested)
            <div class="time_off"> 
                <span> Start Date : {{date('D, d M, Y', strtotime($requested->leave_start))}} </span>
                <span> End Date : {{date('D, d M, Y', strtotime($requested->leave_end))}}</span>
                <span> Hours Requested : {{$requested->hours_requested}}</span>
                <span class="{{$requested->status}}"> {{$requested->status}} </span>
                
                <form action="/cancel_request" method="POST" name="cancel_request">
                    @csrf
                    <input type="hidden" name="request_id" value="{{$requested->id}}">
                    <button type="submit"> Cancel Request </button>
                </form> 
            </div>  
        @endforeach  
    </div>
    @endif
    
    <div style="margin: 10px auto; padding: 10px;">
        <span> Available PTO : {{(auth()->user()->pto_earned) - (auth()->user()->pto_taken)}} hours. </span>
    </div>
    <div>
        <h4> Submit New Request </h4>
        
        <label for="request_form"> Paid Time Off Request Form </label>
        <div class="form">
        <form name="request_form" action="/time_off" method="POST">
            @csrf
            
            <div class="input">
            <label for="start_date"> Start Date </label>
            <input name="start_date" type="date" required>
            </div>
            <div class="input">
            <label for="end_date" type="date"> End Date </label>
            <input name="end_date" type="date" required>
            </div>

            <label for="hours_requested"> Total Hours Requested : </label>
            <input name="hours_requested" type="number" min="0.5" step="0.5" required>
            <button> Submit </button>
        </div>
            
        </form>
    </div>

</x-layout>