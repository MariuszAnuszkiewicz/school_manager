<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\RegisterUserNotification;
use App\Providers\RouteServiceProvider;
use App\Models\Pupil;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'date_of_birth' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:15'],
        ]);
    }

    /**
     * Create a new user (pupil) instance after a valid registration.
     *
     * @param Request $request
     * @return redirect
     */
    public function createPupil(Request $request)
    {
        $data = array_merge([], $request->all());
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'],
            'phone' => $data['phone'],
            'avatar' => 'user-avatar.png',
        ])->assignRole('pupil');
        Pupil::create([
            'user_id' => $user->id,
            'class_in_school_id' => null,
        ]);
        $user->notify(new RegisterUserNotification($user));
        return redirect('login');
    }

    /**
     * Create a new user (teacher) instance after a valid registration.
     *
     * @param Request $request
     * @return redirect
     */
    public function createTeacher(Request $request)
    {
        $data = array_merge([], $request->all());
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'],
            'phone' => $data['phone'],
            'avatar' => 'user-avatar.png',
        ])->assignRole('teacher');
        Teacher::create([
            'user_id' => $user->id
        ]);
        $user->notify(new RegisterUserNotification($user));
        return redirect('login');
    }
}
