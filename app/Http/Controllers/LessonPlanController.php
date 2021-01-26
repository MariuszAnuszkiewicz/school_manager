<?php

namespace App\Http\Controllers;

use App\Models\ClassInSchool;
use App\Models\LessonPlan;
use App\Models\Pupil;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function index(Request $request)
    {
        $pupil = Pupil::where('user_id', auth()->user()->id)->first();
        foreach (LessonPlan::where('class_in_school_id', $pupil->class_in_school_id)->get() as $l) {
            $data['lessonPlan'][] = $l;
        }
        if (!empty($data) && isset($data['lessonPlan'])) {
            if ($request->ajax()) {
                return response()->json([
                    'lessonPlan' => $data['lessonPlan'],
                    'assignClass' => isset(ClassInSchool::find($pupil->class_in_school_id)->name)
                        ? ClassInSchool::find($pupil->class_in_school_id)->name
                        : null,
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => 'There are no any lesson plan.']);
            }
        }
        return view('pupil.lesson_plan.lesson_plan');
    }
}
