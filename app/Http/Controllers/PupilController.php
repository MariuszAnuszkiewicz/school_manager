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
            foreach ($authUser->pupil->ratings as $key => $rating) {
                $data['class_name'] = ClassInSchool::find($rating->pivot->pivotParent->class_in_school_id)->name;
                $data['date'][] = $rating->pivot->created_at->format('Y-m-d H:i');
                $data['ratings'][] = $rating;
                $data['semester'] = $rating->pivot->pivotParent->semesters;
                $data['subjects'] = $rating->pivot->pivotParent->subjects;
                $data['teacher'] = $rating->pivot->pivotParent->teachers[0]->user->name;
                $avgArr[] = (int) $data['ratings'][$key]->rating;
            }
        } else {
            $data = [];
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'avg' => number_format(array_sum($avgArr) / count($data['ratings']), 2),
                    'class_name' => $data['class_name'],
                    'date' => $data['date'],
                    'my_grades' => $this->paginate($data['ratings']),
                    'semesters' => $data['semester'],
                    'subjects' => $data['subjects'],
                    'teacher' => $data['teacher'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "You haven't any ratings with " . Subject::find($id)->name]);
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
