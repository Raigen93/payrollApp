<x-layout>

    <div>
        <form name="job_search" action="/search_job" method="POST">
            @csrf
        <input type="text" placeholder="Job Name" name="job_name">
        <input type="number" placeholder="ID" name=job_id>
        <button> Search </button>
        </form>
    </div>
    <section class="table">
       
        {{$work->links()}}
        
        @foreach ($work as $job)
           <div class="table_row">
            <div class="row_head">
                <span> Job name: {{$job->job_name}}</span>
                <span> Work Order: {{$job->job_id}}</span>
            </div>

            <div class="row_body">
                <div class="total_hours">
                <span> Budget Hours: <strong>{{$job->budget_hours}} </strong></span>
                <span> Hours Worked: <strong>{{$job->hours_worked}} </strong> </span>
                </div>
                
                <div class="dept_hours">
                <span> Engineering: <strong>{{$job->dept_hours_eng}} </strong></span>
                <span> Electrical: <strong>{{$job->dept_hours_elec}} </strong></span>
                <span> Pipe Dept: <strong>{{$job->dept_hours_pipe}} </strong></span>
                <span> Structural: <strong>{{$job->dept_hours_stru}} </strong></span>
                </div>
            </div>
            </div>
        @endforeach
        
        </section>

</x-layout>