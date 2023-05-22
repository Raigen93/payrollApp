<header>
    @auth 
    <div class="content_centered">
        <label for="logout_form" style="margin: 8px;"> Welcome, <strong><em>{{auth()->user()->username}}!</em></strong> </label>
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