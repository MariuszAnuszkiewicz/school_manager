<?php

namespace App\Http\Controllers;

use App\Models\ClassInSchool;
use App\Models\Pupil;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $pupils = Pupil::with('teachers')->orderBy('user_id', 'ASC')->get();
        $data = [];
        foreach ($pupils as $pupil) {
            $data['users'][] = isset($pupil->user) ? $pupil->user: null;
            $data['pupils'][] = isset($pupil) ? $pupil: null;
            $data['assign_classes'][] = isset($pupil->classInSchool) ? $pupil->classInSchool: null;
        }
        foreach (ClassInSchool::all() as $classInSchool) {
            $data['classes_in_school'][] = isset($classInSchool) ? $classInSchool: null;

        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $data['users'],
                    'pupils' => $data['pupils'],
                    'classes_in_school' => $data['classes_in_school'],
                    'assign_classes' => $data['assign_classes'],
                ]);
            }
        }
        return view('teacher.pupils');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $teacher = auth()->user()->teacher;
            if (!empty($request->class_assign) && !empty($request->pupils)) {
                $ids = explode(",", $request->pupils);
                Pupil::whereIn('id', $ids)->update(['class_in_school_id' => (int) $request->class_assign]);
                for ($i = 0; $i < count($ids); $i++) {
                    $data[] = $ids[$i];
                }
                $teacher->pupils()->sync($data);
            }
            return response()->json(['message' => $request->pupils]);
        }
    }
}
