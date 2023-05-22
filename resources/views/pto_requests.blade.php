<x-layout>
    <div>
        <span>
        <button type="button" id="waiting" style="width: 150px;" onclick="hideFinalized()" disabled> Waiting Requests </button> 
        <button type="button" id="finalized" style="width: 150px;" onclick="hideRequests()">  Finalized Requests </button>
        </span>
    </div>

    <section id="waiting_requests" >
    <div style="display: flex; justify-content: space-around;">{{$requests->links()}} </div>
        @foreach ($requests as $request)
        <div> 
            <div class="time_off"> 
                <span> Employee : {{$request->emp_last}}, {{$request->emp_first}} </span> 
                <span> Start Date : {{date('D, d M, Y', strtotime($request->leave_start))}} </span>
                <span> End Date : {{date('D, d M, Y', strtotime($request->leave_end))}}</span>
                <span> Hours Requested : {{$request->hours_requested}}</span>
                <form method="POST" action="/process_pto" name="process_pto">
                    @csrf
                    <input type="hidden" value="{{$request->id}}" name="request_id">
                    <input type="radio" value="approve" name="decision"> Approve </input>
                    <input type="radio" value="deny" name="decision"> Deny </input>
                    <button> Submit </button>
                </form>
            </div>  
        </div>
        @endforeach
    </div>
    </section>

    <section id="final_requests" style="display: none;">
    <div style="display: flex; justify-content: space-around;">{{$finalized->links()}} </div>
        @foreach ($finalized as $finalized)
        <div> 
            <div class="time_off"> 
                <span> Employee : {{$finalized->emp_last}}, {{$finalized->emp_first}} </span> 
                <span> Start Date : {{date('D, d M, Y', strtotime($finalized->leave_start))}} </span>
                <span> End Date : {{date('D, d M, Y', strtotime($finalized->leave_end))}}</span>
                <span> Hours Requested : {{$finalized->hours_requested}}</span>
                <span class="{{$finalized->status}}"> {{$finalized->status}} </span>
            </div>  
        </div>
        @endforeach
    </div>
    </section>
</div>

    <script>
        let waiting = document.getElementById('waiting');
        let finalized = document.getElementById('finalized');
        let waiting_requests = document.getElementById('waiting_requests');
        let final_requests = document.getElementById('final_requests');
        function hideFinalized() {
            final_requests.style.display = 'none';
            waiting_requests.style.display = 'block';
            waiting.setAttribute('disabled',"");
            finalized.removeAttribute('disabled');
        }

        function hideRequests() {
            waiting_requests.style.display = 'none';
            final_requests.style.display = 'block';
            waiting.removeAttribute('disabled');
            finalized.setAttribute('disabled',"");
        }
        
    </script>
</x-layout>