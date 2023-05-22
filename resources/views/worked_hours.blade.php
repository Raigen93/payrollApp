
<x-layout>
    <div class="timetable" id="last_week" style="display:none;">
        <h3 style="flex-basis: 100%;"> Previous Week's Hours: </h3>

            <div class="time">
                <label for="monday"> <em> Monday :</em></label>
                <span  name="monday" id="mon"> </span>
            </div>
            <div class="time">
                <label for="tuesday"> <em> Tuesday :</em></label>
                <span name="tuesday" id="tue"> </span>
            </div>
            <div class="time">
                <label for="wednesday"> <em> Wednesday :</em></label>
                <span name="wednesday" id="wed"> </span>
            </div>
            <div class="time">
                <label for="thursday"> <em> Thursday :</em></label>
                <span name="thursday" id="thur"> </span>
            </div>
            <div class="time">
                <label for="friday"> <em> Friday :</em></label>
                <span name="friday" id="fri"> </span>
            </div>
            <div class="time">
                <label for="saturday"> <em> Saturday :</em></label>
                <span name="saturday" id="sat"> </span>
            </div>
            <div class="time" >
                <label for="sunday"> <em> Sunday :</em></label>
                <span name="sunday" id="sun"> </span>
            </div>
        </div> 

    <section class="main">

        <div id="this_week" class="timetable">
            <h3 style="flex-basis: 100%;"> This Week's Hours </h3>
            <div class="time">
                <label for="mon"> <em> Monday :</em></label>
                <span  name="mon" id="this_mon"> </span>
            </div>
            <div class="time">
                <label for="tue"> <em> Tuesday :</em></label>
                <span name="tue" id="this_tue"> </span>
            </div>
            <div class="time">
                <label for="wed"> <em> Wednesday :</em></label>
                <span name="wed" id="this_wed"> </span>
            </div>
            <div class="time">
                <label for="thu"> <em> Thursday :</em></label>
                <span name="thu" id="this_thu"> </span>
            </div>
            <div class="time">
                <label for="fri"> <em> Friday :</em></label>
                <span name="fri" id="this_fri"> </span>
            </div>
            <div class="time">
                <label for="sat"> <em> Saturday :</em></label>
                <span name="sat" id="this_sat"> </span>
            </div>
            <div class="time" >
                <label for="sun"> <em> Sunday :</em></label>
                <span name="sun" id="this_sun"> </span>
            </div>
        </div>

        <h3> Daily Timesheet: </h3>

            <label for="hours_form"> Submit Daily Hours for <br> <em> {{date("l m-d-Y")}} </em></label>
                <form name="hours_form"  action="/submit_hours" method="POST">
                    @csrf
                    <div style="display: flex; flex-direction: column;">
                    <span style="width: 60%; margin: 5px auto;"> 
                        <label for="start_time" style="margin: 5px;"> Start :</label>
                        <input name="start_time" type="time" required/>
                    </span>
                    <span style="width: 60%; margin: 5px auto;">
                        <label for="stop_time"> Finish : </label>
                    <input name="stop_time" type="time" required/>
                    </span>

                    <span style="width: 60%; margin: 5px auto;">
                        <label for="job_num"> Job Number: </label>
                        <input name="job_num" type="number" />
                    </span>
                    </div>
                    <button style="margin-top: 10px;"> Submit </button>
                </form>
    </section>

    <script>
        fetch('/weekly_time', {
            method: 'POST',
            headers : {
                'Content-type' : 'application/json',
                'Accept' : 'application/json',
                'url' : '/weekly_time',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            }
        }).then(function(data){
            return data.json();
        }).then(function(data){
            return data[0];
        }).then(function(data){
            document.getElementById('mon').innerHTML = data.Monday + " hours";
            document.getElementById('tue').innerHTML = data.Tuesday + " hours";
            document.getElementById('wed').innerHTML = data.Wednesday + " hours";
            document.getElementById('thur').innerHTML = data.Thursday + " hours";
            document.getElementById('fri').innerHTML = data.Friday + " hours";
            document.getElementById('sat').innerHTML = data.Saturday + " hours";
            document.getElementById('sun').innerHTML = data.Sunday + " hours";
        });

        fetch('/this_week', {
            method: 'POST',
            headers : {
                'Content-type' : 'application/json',
                'Accept' : 'application/json',
                'url' : '/weekly_time',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            }
        }).then(function(data){
            return data.json() ;
        }).then(function(data){
            document.getElementById('this_mon').innerHTML = data.Monday + " hours";
            document.getElementById('this_tue').innerHTML = data.Tuesday + " hours";
            document.getElementById('this_wed').innerHTML = data.Wednesday + " hours";
            document.getElementById('this_thu').innerHTML = data.Thursday + " hours";
            document.getElementById('this_fri').innerHTML = data.Friday + " hours";
            document.getElementById('this_sat').innerHTML = data.Saturday + " hours";
            document.getElementById('this_sun').innerHTML = data.Sunday + " hours";
        });

    </script>
</x-layout>