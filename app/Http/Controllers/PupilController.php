<?php

namespace App\Http\Controllers;

use App\Mail\SendToTeacher;
use App\Models\ClassInSchool;
use App\Models\Pupil;
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
        $authUser = auth()->user();
        if ((int) $id === $authUser->pupil->subjects[0]->id) {
           // $data = [];
            foreach (Pupil::find($authUser->pupil->id)->semesters as $semester) {
                $data['semester'][] = $semester;
            }
            foreach ($authUser->pupil->subjects as $subject) {
                $data['subjects'] = $subject;
            }
            foreach ($authUser->pupil->teachers as $teacher) {
                $data['teacher'][] = $teacher->user->name;
            }
            foreach ($authUser->pupil->ratings as $key => $rating) {
                $data['class_name'] = ClassInSchool::find($rating->pivot->pivotParent->class_in_school_id)->name;
                $data['ratings'][] = $rating;
                $data['date'][] = $rating->pivot->created_at->format('Y-m-d H:i');
                $avgArr[] = (int)$data['ratings'][$key]->rating;
            }
        } else {
            $data = [];
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'semesters' => $data['semester'],
                    'class_name' => $data['class_name'],
                    'teacher' => $data['teacher'],
                    'avg' => number_format(array_sum($avgArr) / count($data['ratings']), 2),
                    'subjects' => $data['subjects'],
                    'my_grades' => $this->paginate($data['ratings']),
                    'date' => $data['date'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "You haven't any ratings with " . Subject::find($id)->name]);
            }
        }


//        $subjectsWith = Subject::with('ratings')->where('id', '=', $id)->get();
//        $ratings = [];
//        $subjects = [];
//        $date = [];
//        foreach ($subjectsWith as $subject) {
//            foreach ($subject->ratings as $rating) {
//                array_push($ratings, $rating->rating);
//                array_push($subjects, $subject->name);
//                array_push($date, $rating->pivot->created_at->format('Y-m-d H:i'));
//                foreach ($rating->pupils as $pupil) {
//                    $semester = $pupil->semesters;
//                    $className = $pupil->classInSchool->name;
//                }
//                foreach ($pupil->teachers as $teacher);
//            }
//        }
//        $data = [
//            'rating' => $ratings,
//            'subject' => isset($subjects) ? $subjects : null ,
//            'semester' => isset($semester) ? $semester : null,
//            'class_name' => isset($className) ? $className : null,
//            'teacher' => isset($teacher->user->name) ? $teacher->user->name : null,
//            'date' => isset($date) ? $date : null,
//        ];
//        if (!empty($data)) {
//            if (isset($pupil) && $pupil->user->id === auth()->user()->id) {
//                if ($request->ajax()) {
//                    return response()->json([
//                        'semesters' => $data['semester'],
//                        'class_name' => $data['class_name'],
//                        'teacher' => $data['teacher'],
//                        'avg' => number_format(array_sum($data['rating']) / count($data['rating']), 2),
//                        'subjects' => $subject->name,
//                        'my_grades' => $this->paginate($data['rating']),
//                        'date' => $data['date'],
//                    ]);
//                }
//            } else {
//                if ($request->ajax()) {
//                    return response()->json(['message' => "You haven't any ratings with $subject->name"]);
//                }
//            }
//        }
        return view('pupil.my_grades');
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
