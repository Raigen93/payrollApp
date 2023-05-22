<x-layout>

    @auth
    <section class="main">
        <div class="icons">
            <span class="icon_container">
                <p class="icon"> <a href="/worked_hours"> Hours </a></p>
            </span>
            <span class="icon_container">
                <p class="icon"> <a href="/time_off"> PTO </a></p>
            </span>
            <span class="icon_container"> 
                <p class="icon"> <a href="/weekly_time"> PayStubs </a></p>
            </span>

            <!-- if user is ADMIN section -->
            @if (auth()->user()->isAdmin !== 0)
            <span class="icon_container">
                <p class="icon"> <a href="/pto_requests"> PTO Requests </a></p>
            </span>
            <span class="icon_container">
                <p class="icon"> <a href="/project_hours">Projects </a></p>
            </span>
            <span class="icon_container"> 
                <p class="icon"> <a href="/create_job_form"> Register New Jobs </a></p>
            </span>
            <span class="icon_container"> 
                <p class="icon"> <a href="/add_employee"> Add Employees </a></p>
            </span>
            @endif
            <span class="icon_container">
                <p class="icon"> <a href="/news"> Company News </a></p>
            </span>
           <!--
            <span>
                <a href="/testing"> Testing </a>
            </span>
            -->
        </div>


        <h3 style="text-decoration: underline;"> Recent News </h3>
        <div class="company_news">
            <div> 
                <h4 class="news_title" id="news_title"> </h4>
                <p class="news_body" id="news_body"> </p>
                <em><span class="author" id="author"> </span></em> 
            </div>
        </div>
    </section>
    @else
    <section class="main">
        <div>
            <h1>
                Welcome to <em><strong>PayDay Manager</strong></em>
            </h1>
            <p> <em> A management system for all your Human Resources needs. </em> </p> 
        </div>

        <div class="flex_horizon">
            <img id="splash" src="{{url('/background.jpg')}}" alt='multi-color splash image'/>
            <p style="width: 90%; margin: 10px auto;"> Schedule time off, review PayStubs, review your scheduled and worked hours, and stay up on the latest news from your company. </p>
        </div>
    @endauth

    </section>


    <script>
        fetch('/home_news',
            {
            method: 'GET',
            headers : {
                'Content-type' : 'application/json',
                'Accept' : 'application/json',
                'url' : '/home_news',
            }
        })
        .then(function(data) {
            return data.json();
        })
        .then(function(data) {
            document.getElementById('news_title').innerText = data.title;
            document.getElementById('news_body').innerText = data.body;
            document.getElementById('author').innerText = '-' + data.author;
        });
    </script>
</x-layout>