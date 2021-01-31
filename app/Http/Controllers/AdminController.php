<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        //dd(auth()->user()->roles->first()->name);
    }

    public function assignSubjectsForTeachers(Request $request)
    {
        $data['teachers'] = Teacher::all();
        $data['subjects'] = Subject::all();
        foreach ($data['teachers'] as $teacher) {
            $data['users'][] = User::find($teacher->user_id);
            $data['subjectAssign'] = Teacher::find($teacher->id)->subjects;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'teachers' => isset($data['teachers']) ? $data['teachers'] : null,
                    'subjects' => isset($data['subjects']) ? $data['subjects'] : null,
                    'subjectAssign' => isset($data['subjectAssign']) ? $data['subjectAssign'] : null,
                    'users' => isset($data['users']) ? $data['users'] : null,
                ]);
            }
        } else {
            if ($request->ajax()) {

            }
        }
        return view('admin.assign_subject_for_teacher');
    }

    public function subjectAssign(Request $request)
    {
        if ($request->ajax()) {
            Teacher::find($request->teacher)->subjects()->update(['subject_id' => (int) $request->subject_assign]);
        }
        return response()->json(['message' => 'Subject has been updated successfully.']);
    }
}
