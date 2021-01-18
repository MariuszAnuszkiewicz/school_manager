<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function myTeachers(Request $request)
    {
        foreach (isset(auth()->user()->pupil->teachers) ? auth()->user()->pupil->teachers : null as $teacher) {
            $data['teachers'][] = $teacher->user;
            $data['subjects'][] = auth()->user()->pupil->subjects->first()->name;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'teachers' => $data['teachers'],
                    'subjects' => $data['subjects']
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There are no any My Teachers."]);
            }
        }
        return view('pupil.my_teachers');
    }
}
