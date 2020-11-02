<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendToTeacher;

class PupilController extends Controller
{
    public function sendEmail(Request $request)
    {
        if ($request->ajax()) {
            Mail::to($request->email)->send(new SendToTeacher(
                [
                    'pupil' => auth()->user()->name,
                    'message' => $request->message,
                ]
            ));
        }
    }
}
