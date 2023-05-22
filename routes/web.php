<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WeeklyController;
use App\Http\Controllers\TimeOffController;
use App\Http\Controllers\TimesheetController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Routes where login isn't required
Route::get('/', function () {
    return view('homepage');
})->name('home');
Route::post('/login', [UserController::class, "login"]);

//Routes requiring user to be logged in
Route::middleware(['auth'])->group(function () {
    //news Routes
Route::get('/news', [NewsController::class, 'news_page']);
Route::post('/news', [NewsController::class, 'create_news']);
Route::get('/home_news', [NewsController::class, 'home_news']);

    //user routes
Route::post('/register', [UserController::class, "register"]);
Route::post('/logout', [UserController::class, "logout"]);

    //worked hours routes
Route::get('/worked_hours', [UserController::class, "worked_hours"]);
Route::post('/submit_hours', [UserController::class, "submit_hours"]);
Route::get('/weekly_time', [WeeklyController::class, "getAll"]);
Route::post('/this_week', [TimesheetController::class, "getThisWeek"]);

    //PTO routes
Route::get('/time_off', [TimeOffController::class, "time_off"]);
Route::post('/time_off', [TimeOffController::class, "submit_time_request"]);
Route::post('/cancel_request', [TimeOffController::class, "cancel_time"]);
});

//Routes requiring user to be logged in & isAdmin
Route::middleware(['auth', 'admin'])->group(function () {
    //add a new user
Route::get('/add_employee', function () {   return view('add_employee');    });
    //routes for creating new jobs
Route::post('/make_job', [WorkController::class, "make_job"]);
Route::get('/create_job_form', function () {    return view('create_job_form');     });

    //Routes for viewing jobs
Route::get('/project_hours', [WorkController::class, "project_hours"]);
Route::post('/search_job', [WorkController::class, "search"]);
Route::get('/search_job', [WorkController::class, "project_hours"]);

    //view & approve or deny PTO
Route::get('/pto_requests', [TimeOffController::class, "view_requests"]);
Route::post('/process_pto', [TimeOffController::class, "process_pto"]);

//Testing
Route::get('/testing', [TimesheetController::class, "pto_timesheet"]);
});