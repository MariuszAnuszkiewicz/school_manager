<?php

namespace App\Http\Controllers;

use App\Models\LessonPlan;
use App\Models\Pupil;
use App\Models\ClassInSchool;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function index(Request $request)
    {
        $pupil = Pupil::where('user_id', auth()->user()->id)->first();
        $lessonPlan = LessonPlan::where('class_in_school_id', $pupil->class_in_school_id)->get();
        $class = ClassInSchool::find($pupil->class_in_school_id);
        if ($request->ajax()) {
            return response()->json(['lessonPlan' => $lessonPlan, 'class' => $class->name]);
        }
        return view('pupil.lesson_plan');
    }
}
