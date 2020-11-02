<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function myTeachers(Request $request)
    {
        $pupil = auth()->user()->pupil;
        $arrayTeachers = [];
        $arraySubjects = [];
        foreach ($pupil->teachers as $teacher) {
            $arrayTeachers[] = $teacher->user;
            foreach ($teacher->subjects as $subject) {
                $arraySubjects[] = $subject->name;
            }
        }
        if ($request->ajax()) {
            return response()->json([
                'teachers' => $arrayTeachers,
                'subjects' => $arraySubjects
            ]);
        }
        return view('pupil.my_teachers');
    }
}
