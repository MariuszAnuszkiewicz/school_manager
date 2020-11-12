<?php

namespace App\Http\Controllers;

use App\Mail\SendToTeacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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

    public function myGrades(Request $request, $id)
    {
        $subjectsWith = Subject::with('ratings')->where('id', '=', $id)->get();
        $ratings = [];
        $subjects = [];
        $date = [];
        foreach ($subjectsWith as $subject) {
            foreach ($subject->ratings as $rating) {
                array_push($ratings, $rating->rating);
                array_push($subjects, $subject->name);
                array_push($date, $rating->pivot->created_at->format('Y-m-d H:i'));
                foreach ($rating->pupils as $pupil) {
                    $semester = $pupil->semesters;
                    $className = $pupil->classInSchool->name;
                }
                foreach ($pupil->teachers as $teacher);
            }
        }
        $data = [
            'rating' => $ratings,
            'subject' => isset($subjects) ? $subjects : null ,
            'semester' => isset($semester) ? $semester : null,
            'class_name' => isset($className) ? $className : null,
            'teacher' => isset($teacher->user->name) ? $teacher->user->name : null,
            'date' => isset($date) ? $date : null,
        ];
        if (!empty($data)) {
            if (isset($pupil) && $pupil->user->id === auth()->user()->id) {
                if ($request->ajax()) {
                    return response()->json([
                        'semesters' => $data['semester'],
                        'class_name' => $data['class_name'],
                        'teacher' => $data['teacher'],
                        'avg' => number_format(array_sum($data['rating']) / count($data['rating']), 2),
                        'subjects' => $subject->name,
                        'my_grades' => $this->paginate($data['rating']),
                        'date' => $data['date'],
                    ]);
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['message' => "You haven't any ratings with $subject->name"]);
                }
            }
        }
        return view('pupil.my_grades');
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
