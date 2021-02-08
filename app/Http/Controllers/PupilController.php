<?php

namespace App\Http\Controllers;

use App\Mail\SendToTeacher;
use App\Models\ClassInSchool;
use App\Models\Subject;
use App\Http\Requests\Pupil\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PupilController extends Controller
{
    public function sendEmail(SendMessageRequest $request)
    {
        $input = $request->validated();
        if ($request->ajax()) {
            Mail::to($request->email)->send(new SendToTeacher(
                [
                    'pupil' => auth()->user()->name,
                    'email_message' => (string) $input['email_message'],
                ]
            ));
        }
        return response()->json(['message' => 'Send email was successfully.']);
    }

    public function myGrades(Request $request, $id)
    {
        $authUser = auth()->user();
        $hasSubject = isset($authUser->pupil->subjects) ? $authUser->pupil->subjects->first()->id : null;
        if ((int) $id === $hasSubject) {
            foreach ($authUser->pupil->ratings as $rating) {
                $data['className'] = ClassInSchool::find($rating->pivot->pivotParent->class_in_school_id)->name;
                $data['date'][] = $rating->pivot->created_at->format('Y-m-d H:i');
                if ($rating->pivot->semester === 1) {
                    $data['ratingsSem1'][] = $rating;
                    $data['semester1'] = $rating->pivot->semester;
                } else {
                    $data['ratingsSem2'][] = $rating;
                    $data['semester2'] = $rating->pivot->semester;
                }
                $data['subjects'] = $rating->pivot->pivotParent->subjects;
                $data['teacher'] = $rating->pivot->pivotParent->teachers->first()->user->name;
            }
            if (!empty($data['ratingsSem1'])) {
                for ($i = 0; $i < count($data['ratingsSem1']); $i++) {
                    $avgArr['sem1'][] = (int) $data['ratingsSem1'][$i]->rating;
                }
            }
            if (!empty($data['ratingsSem2'])) {
                for ($i = 0; $i < count($data['ratingsSem2']); $i++) {
                    $avgArr['sem2'][] = (int) $data['ratingsSem2'][$i]->rating;
                }
            }
        } else {
            $data = [];
        }
        $avgFunc = function(array $avgArr, array $data) {
            return number_format(array_sum($avgArr) / count($data), 2);
        };
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'avgSem1' => isset($avgArr['sem1']) ? $avgFunc($avgArr['sem1'], $data['ratingsSem1']) : null,
                    'avgSem2' => isset($avgArr['sem2']) ? $avgFunc($avgArr['sem2'], $data['ratingsSem2']) : null,
                    'className' => isset($data['className']) ? $data['className'] : null,
                    'date' => isset($data['date']) ? $data['date'] : null,
                    'myGradesSem1' => isset($data['ratingsSem1']) ? $data['ratingsSem1'] : null,
                    'myGradesSem2' => isset($data['ratingsSem2']) ? $data['ratingsSem2'] : null,
                    'semester1' => isset($data['semester1']) ? $data['semester1'] : null,
                    'semester2' => isset($data['semester2']) ? $data['semester2'] : null,
                    'subjects' => isset($data['subjects']) ? $data['subjects'] : null,
                    'teacher' => isset($data['teacher']) ? $data['teacher'] : null,
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
