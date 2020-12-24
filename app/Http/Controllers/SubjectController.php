<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function myTeachers(Request $request)
    {
        $data = [];
        foreach (auth()->user()->pupil->teachers as $teacher) {
            $data['teachers'][] = $teacher->user;
            foreach ($teacher->subjects as $subject) {
                $data['subjects'][] = $subject->name;
            }
        }
        if(!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'teachers' => $data['teachers'],
                    'subjects' => $data['subjects']
                ]);
            }
        }
        return view('pupil.my_teachers');
    }
}
