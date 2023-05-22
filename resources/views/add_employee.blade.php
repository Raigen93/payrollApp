<x-layout>
    <div id="reg_form" class="form">
        <label for="reg_form"> <strong> Register a New Employee </strong> </label>
        <form name="reg_form" action="/register" method="POST">
        @csrf
            <input type="text" placeholder="First Name" name="first_name" required/>
            <input type="text" placeholder="Last Name" name="last_name" required/>
            <input type="email" placeholder="Email" name="email" required/>
            <input type="password" placeholder="Password" name="password" required/>
            <label for="password" style="font-size:11px;"> *Passwords must be at least 10 characters long </label>
            <input type="password" placeholder="Re-enter Password" name="password_confirmation" required/>
            <input type="string" placeholder="Department" name="department" required/>
            <button> Submit </button>
        </form>
    </div> 
</x-layout>