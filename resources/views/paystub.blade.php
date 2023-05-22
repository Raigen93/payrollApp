<x-layout>

    <div class="paystubs">
        
            <label for="weeks"> Period End Date : </label>
            <select id="weeks" onchange="setStub()">
                <option value="null"> </option>
                @foreach ($stubs as $stub)
                    <option class="week_end" value="{{date('m/d/Y', strtotime($stub->created_at))}}"> {{date('m/d/Y', strtotime($stub->created_at))}} </option>
                @endforeach
            </select>


    @foreach ($stubs as $stub)
            
            <div class="paystub" id="{{date('m/d/Y', strtotime($stub->created_at))}}" style="display: none;">
                <div class="info">
                <div class="slot">
                    <label for="employee"> Employee </label>
                    <span name="employee"> {{$stub->employee_last}}, {{$stub->employee_first}}</span>
                </div>
                <div class="slot">
                    <label for="wage"> Hourly Wage </label>
                    <span name="wage"> {{auth()->user()->wage}} </span>
                </div>
                <div class="slot">
                    <label for="weekof"> Period End </label>
                    <span name="weekof"> {{date('m/d/Y', strtotime($stub->created_at))}} </span>
                </div>
                </div>
                <div class="info">
                <div class="slot">
                    <label for="hours"> Hours Worked </label>
                    <span name="hours"> {{$stub->total_hours}} </span>
                </div>
                <div class="slot">
                    <label for="gross"> Gross Earnings </label>
                    <span name="gross"> {{$stub->gross}} </span>
                </div>
                <div class="slot">
                    <label for="net"> Net Earnings </label>
                    <span name="net"> {{$stub->net}} </span>
                </div>
                <div class="slot">
                    <label for="tax"> Tax </label>
                    <span name="tax"> {{$stub->tax}} </span>
                </div>
                </div>
            </div>
    
    @endforeach
    </div>

    <script>
        let stubs = Array.from(document.querySelectorAll('.paystub'));
        
        function setStub() {
            let selectedWeek = document.getElementById('weeks').value;

            stubs.forEach(stub => {
                if(stub.id === selectedWeek) {
                    stub.style.display = 'flex';
                } else {
                    stub.style.display = 'none';
                }
            });

        }
    
    </script>

</x-layout>