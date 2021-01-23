<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PupilController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\TeacherController;

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

     /* user profile zone */
    Route::group([
        'prefix' => 'user_profile',
        'middleware' => 'auth',
    ], function () {
        Route::get('user/{id}', [UserProfileController::class, 'show'])->name('user_profile');
        Route::post('user', [UserProfileController::class, 'updateAvatar']);
        Route::put('user/{id}', [UserProfileController::class, 'updateProfile']);
    });

    /* pupil zone */
    Route::group([
        'prefix' => 'pupil',
        'middleware' => ['guard_access', 'auth'],
    ], function () {
        Route::resource('events', EventController::class)->only(['index', 'destroy']);
        Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
        Route::resource('lesson-plan', LessonPlanController::class)->only(['index']);
        Route::get('my-teachers', [SubjectController::class, 'myTeachers']);
        Route::post('send-email', [PupilController::class, 'sendEmail']);
        Route::get('my-grades/{subject}', [PupilController::class, 'myGrades'])->where('subject', '[0-9]+');

    });

    /* teacher zone */
    Route::group([
        'prefix' => 'teacher',
        'middleware' => 'guard_access',
    ], function () {
        Route::resource('pupils', TeacherController::class)->only(['index']);
        Route::post('save-pupil-teacher', [TeacherController::class, 'savePupilTeacher']);
        Route::post('save-pupil-semester', [TeacherController::class, 'savePupilSemester']);
        Route::post('save-pupil-subject', [TeacherController::class, 'savePupilSubject']);
        Route::post('update-pupils', [TeacherController::class, 'updatePupils']);
        Route::get('selected-pupils', [TeacherController::class, 'selectedPupils']);
        Route::post('delete-pupil-teacher', [TeacherController::class, 'deletePupilTeacher']);
        Route::post('delete-pupil-semester', [TeacherController::class, 'deletePupilSemester']);
        Route::post('send-message', [TeacherController::class, 'sendMessage']);
        Route::get('my-messages', [TeacherController::class, 'myMessages']);
        Route::put('update-message/{id}', [TeacherController::class, 'updateMessage'])->where('id', '[0-9]+');
        Route::post('delete-messages', [TeacherController::class, 'deleteMessages']);
        Route::get('single-message/{id}', [TeacherController::class, 'singleMessage'])->where('id', '[0-9]+');
        Route::get('list-emails', [TeacherController::class, 'listEmails']);
        Route::post('send-emails', [TeacherController::class, 'sendEmails']);
        Route::get('assign-rating', [TeacherController::class, 'assignRating']);
        Route::post('save-pupil-rating', [TeacherController::class, 'savePupilRating']);
        Route::get('pupil-rating/{id}', [TeacherController::class, 'getRatingsByPupilId'])->where('id', '[0-9]+');
        Route::post('update-pupil-rating', [TeacherController::class, 'updatePupilRating']);
        Route::post('delete-pupil-rating', [TeacherController::class, 'deletePupilRating']);
        Route::get('list-ratings', [TeacherController::class, 'listRatings']);
        Route::get('detail-rating/{id}', [TeacherController::class, 'detailRating'])->where('id', '[0-9]+');
        Route::get('fill-presence', [TeacherController::class, 'fillPresence']);
        Route::post('save-presence', [TeacherController::class, 'savePresence']);
        Route::get('detail-presence/{id}', [TeacherController::class, 'detailPresence'])->where('id', '[0-9]+');
        Route::delete('detail-presence/delete/{id}', [TeacherController::class, 'deletePresence'])->where('id', '[0-9]+');
        Route::put('detail-presence/update', [TeacherController::class, 'updatePresence']);
        Route::get('events-by-calendar', [EventController::class, 'eventsByCalendar']);
        Route::get('list-events', [EventController::class, 'listEventsByTeacher']);
        Route::post('save-events', [EventController::class, 'saveEvents']);
        Route::post('save-event-teacher', [EventController::class, 'saveEventTeacher']);
        Route::delete('delete-event/{id}', [EventController::class, 'deleteEventByTeacher'])->where('id', '[0-9]+');
        Route::put('update-event/{id}', [EventController::class, 'updateEvent'])->where('id', '[0-9]+');
    });

/*********************************************************************************************************************/
