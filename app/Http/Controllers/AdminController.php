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

    public function listSubjectsForTeachers(Request $request)
    {
        $data['teachers'] = Teacher::all();
        $data['subjects'] = Subject::all();
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'teachers' => isset($data['teachers']) ? $data['teachers'] : null,
                    'subjects' => isset($data['subjects']) ? $data['subjects'] : null,
                ]);
            }
        } else {
            if ($request->ajax()) {

            }
        }
        return view('admin.list_subjects_for_teachers');
    }
}
