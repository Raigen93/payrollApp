<x-layout>
    <div id="make_job" class="form">
        <label for="make_job_form"> <strong> Register a New Job </strong> </label>
        <form name="make_job_form" action="/make_job" method="POST">
            @csrf
            <input type="number" placeholder="Job ID" name="job_id" required>
            <input type="number" placeholder="Budget Hours" name="budget_hours" required>
            <input type="text" placeholder="Job Name" name="job_name" required>
            <button> Submit </button>    
        </form>    
    </div>
</x-layout>