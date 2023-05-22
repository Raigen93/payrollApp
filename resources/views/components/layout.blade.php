<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        PayRoll App
    </title>
    @vite(['resources/css/app.css'])
  </head>
 
  <body class="content_centered">
    <header>
    @auth 
    <div class="content_centered">
        <label for="logout_form" style="margin: 8px;"> Welcome, <strong><em>{{auth()->user()->first_name}}!</em></strong> </label>
    <form name="logout_form" action="/logout" method="POST" style="margin: 8px;">
        @csrf
        <button > Sign Out </button>
    </form>
    </div>
    @else
    <form action="/login" method="POST">
        @csrf
        <div>
            <input type="text" placeholder="Username" name="loginusername" required/> 
            <input type="password" placeholder="Password" name="loginpassword" required/>
            <button> Sign In </button>
        </div>
    </form>
    @endauth
</header>
<!-- header ends -->

<section class="main_content">
    @if (session()->has('success'))
<div>
    <div class="alert-success">
    {{session('success')}}
    </div>
</div>
@endif

@if (session()->has('failure'))
<div>
    <div class="alert-danger">
    {{session('failure')}}
    </div>
</div>
@endif

@if ($errors->any())
<div>
    <div class="alert-danger">
        @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
        @endforeach
    </div>
</div>
@endif
{{$slot}}

</section>

<!-- footer begins -->

<footer>
    @auth
    <span class="footer_button">
        <a href="/"> Home </a>
    </span>
    <span class="footer_button">
        <a href="/worked_hours"> Hours </a>
    </span>
    <span class="footer_button">
        <a href="/weekly_time"> PayStubs </a>
    </span>
    <span class="footer_button">
        <a href="/time_off"> PTO </a>
    </span>
    @else 
    <span> {{date('D, M d Y')}} </span>
    @endauth
</footer>
  </body>
</html>