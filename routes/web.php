<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PupilController;
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

    /*  pupil zone */
    Route::group([
        'prefix' => 'pupil',
        'middleware' => 'auth',
    ], function () {
        Route::resource('events', EventController::class)->only(['index', 'destroy']);
        Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
        Route::resource('lesson-plan', LessonPlanController::class)->only(['index']);
        Route::get('my-teachers', [SubjectController::class, 'myTeachers']);
        Route::post('send-email', [PupilController::class, 'sendEmail']);
    });



/*********************************************************************************************************************/
