<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Auth Pupil and Teacher
/*********************************************************************************************************************/
    Auth::routes();

    Route::get('/register-pupil', function () {
        return view('auth.register_pupil');
    })->name('register_pupil');
    Route::get('/register-teacher', function () {
        return view('auth.register_teacher');
    })->name('register_teacher');

    Route::post('create-pupil', [RegisterController::class, 'createPupil'])->name('create_pupil');
    Route::post('create-teacher', [RegisterController::class, 'createTeacher'])->name('create_teacher');
/*********************************************************************************************************************/

// inside system endpoints
/*********************************************************************************************************************/
    Route::get('/home', [HomeController::class, 'index'])->name('home');


/*********************************************************************************************************************/
