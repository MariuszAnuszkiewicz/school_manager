<?php

namespace App\Http\Controllers;

use App\Mail\SendToPupil;
use App\Models\ClassInSchool;
use App\Models\Pupil;
use App\Models\Message;
use App\Models\Rating;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

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
                    $data[] = (int) $ids[$i];
                }
                $tableIds = [];
                foreach ($teacher->pupils as $pupil) {
                    $tableIds[] = $pupil->id;
                }
                if ($tableIds != $data) {
                    $uniqueIds = array_diff($data, $tableIds);
                    $teacher->pupils()->attach($uniqueIds);
                }
                return response()->json(['message' => 'pupils has been assign to classes']);
            }
        }
    }

    public function selectedPupils(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $data = [];
        foreach ($teacher->pupils as $pupil) {
            $data['users'][] = isset($pupil->user) ? $pupil->user: null;
            $data['pupils'][] = isset($pupil) ? $pupil: null;
            $data['assign_classes'][] = isset($pupil->classInSchool) ? $pupil->classInSchool: null;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $data['users'],
                    'pupils' => $data['pupils'],
                    'assign_classes' => $data['assign_classes'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any pupils"]);
            }
        }
        return view('teacher.selected_pupils');
    }

    public function deletePupils(Request $request)
    {
        auth()->user()->teacher->pupils()->detach($request->selected);
        return response()->json(['message' => 'pupils has been deleted']);
    }

    public function sendMessage(Request $request)
    {
        if ($request->ajax()) {
            $message = Message::create([
                'teacher_id' => auth()->user()->teacher->id,
                'message' => $request->message,
            ]);
            $switchSingleMultipleIds = isset($request->pupil_id) ? $request->pupil_id : $request->selected;
            $message->pupils()->attach($switchSingleMultipleIds);
            return response()->json(['message' => 'Message send successfully']);
        }
    }

    public function myMessages(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $data['teacher'] = $teacher->user->name;
        $data['my_messages'] = $teacher->messages;
        $ids = [];
        foreach ($data['my_messages'] as $message) {
           if ($message->id) {
               foreach ($message->pupils as $pupil) {
                   $ids[$message->id][] = $pupil->id;
               }
           }
           $data['pupils'][] = implode(",", $ids[$message->id]);
        }
        if ($data['my_messages']->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'teacher' => $data['teacher'],
                    'my_messages' => $this->paginate($data['my_messages']),
                    'pupils' => $data['pupils'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any my messages"]);
            }
        }
        return view('teacher.my_messages');
    }

    public function updateMessage(Request $request)
    {
        if ($request->ajax()) {
            Message::where('id', $request->id)->update(['message' => $request->message]);
        }
        return response()->json(['message' => 'Your message has been updated successfully.']);
    }

    public function deleteMessages(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->selected)) {
                Message::whereIn('id', $request->selected)->delete();
            }
        }
        return response()->json(['message' => 'messages has been deleted']);
    }

    public function singleMessage(Request $request, $id)
    {
        $data['message'] = Message::where('id', $id)->first();
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => $data['message'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any my messages"]);
            }
        }
        return view('teacher.single_message');
    }

    public function listEmails(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $pupils = $teacher->pupils;
        $data = [];
        $userIds = [];
        foreach($pupils as $pupil) {
            $userIds[] = $pupil->user_id;
        }
        $users = User::whereIn('id', $userIds)->get();
        foreach($users as $user) {
            $data['pupils'][] = $user;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'pupils' => $this->paginate($data['pupils']),
                ]);
            }
        }
        return view('teacher.list_emails');
    }

    public function sendEmails(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->selectedEmails as $email) {
                Mail::to($email)->send(new SendToPupil(
                    [
                        'teacher' => auth()->user()->name,
                        'message' => $request->message,
                    ]
                ));
            }
        }
        return response()->json(['message' => 'email has been send']);
    }

    public function assignRating(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $pupils = isset($teacher->pupils) ? $teacher->pupils : null;
        $data = [];
        foreach($pupils as $pupil) {
            $data['users'][] = $pupil->user;
            $data['pupils'][] = $pupil;
        }
        $data['subjects'] = $teacher->subjects;
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $this->paginate($data['users'], 7),
                    'pupils' => $this->paginate($data['pupils'], 7),
                    'subjects' => $data['subjects'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any ratings for pupils"]);
            }
        }
        return view('teacher.assign_rating');
    }

    public function getRatingsByPupilId(Request $request, $id)
    {
        $pupils = Pupil::find($id);
        $data = [];
        foreach ($pupils->ratings as $rating) {
            $data['ratings'][] = $rating->rating;
            $data['createAt'][] = $rating->pivot->created_at;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'ratings' => $data['ratings'],
                    'createAt' => $data['createAt'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any ratings for pupils"]);
            }
        }
    }

    public function savePupilRating(Request $request)
    {
        $pupilId = User::find($request->userId)->pupil->id;
        if ($request->ajax()) {
            Pupil::find($pupilId)->ratings()->attach(['rating_id' => $request->rating]);
        }
        return response()->json(['message' => 'rating has been assign']);
    }

    public function saveRatingSubject(Request $request)
    {
        if ($request->ajax()) {
            Subject::find($request->subject)->ratings()->attach(['rating_id' => $request->rating]);
        }
    }

    public function updatePupilRating(Request $request)
    {
        if ($request->ajax()) {
            DB::table('pupil_rating')->where([
                ['pupil_id', '=', User::find($request->userId)->pupil->id],
                ['rating_id', '=', $request->dataRating],
                ['created_at', '=', $request->dataCreate],
            ])->update(['rating_id' => $request->rating]);
        }
        return response()->json(['message' => 'rating has been updated']);
    }

    public function updateRatingSubject(Request $request)
    {
        if ($request->ajax()) {
            DB::table('rating_subject')->where([
                ['rating_id', '=', $request->dataRating],
                ['subject_id', '=', (int) $request->subject],
                ['created_at', '=', $request->dataCreate],
            ])->update(['rating_id' => $request->rating]);
        }
        return response()->json(['message' => 'rating has been updated']);
    }

    public function deletePupilRating(Request $request)
    {
        list($rating) = $request->rating;
        list($ratingArg, $createAtArg) = explode("|", $rating);
        if ($request->ajax()) {
             Pupil::find(User::find($request->userId)->pupil->id)->ratings()
                 ->wherePivot('created_at', '=', $createAtArg)->detach($ratingArg);
        }
        return response()->json(['message' => 'rating has been deleted']);
    }

    public function deleteRatingSubject(Request $request)
    {
        list($rating) = $request->rating;
        list($ratingArg, $createAtArg) = explode("|", $rating);
        if ($request->ajax()) {
            Subject::find($request->subject)->ratings()
                ->wherePivot('created_at', '=', $createAtArg)->detach($ratingArg);
        }
        return response()->json(['message' => 'rating has been deleted']);
    }

    public function listRatings(Request $request)
    {
        $authUser = auth()->user();
        $data = [];
        $userIds = [];
        foreach ($authUser->teacher->pupils as $pupil) {
            $data['users'][] = $pupil->user;
            $data['subject'] = $pupil->subjects;
            foreach ($pupil->ratings as $key => $rating) {
                $userIds[$pupil->user->id][] = $rating->rating;
            }
        }
        $gradeList = [];
        foreach ($userIds as $key => $ids) {
            $gradeList[$key] = implode(", ", $ids);
        }
        $data['ratings'] = $gradeList;
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $this->paginate($data['users'], 7),
                    'ratings' => $data['ratings'],
                    'subject' => $data['subject'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any ratings for pupils"]);
            }
        }
        return view('teacher.list_ratings');
    }

    public function detailRating(Request $request, $id)
    {
        $data = [];
        $pupil = Pupil::find(User::find($id)->pupil->id);
        $data['users'][] = User::find($id);
        $data['ratings'] = $pupil->ratings;
        $data['subject'] = $pupil->subjects;
        foreach ($data['ratings'] as $rating) {
            $data['created_at'][] = isset($rating->pivot->created_at) ? $rating->pivot->created_at : null;
        }
        if (!empty($data['ratings'] && !empty($data['created_at']))) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $data['users'],
                    'ratings' => $data['ratings'],
                    'subject' => $data['subject'],
                    'createdAt' => isset($data['created_at']) ? $data['created_at'] : null,
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any ratings for pupils"]);
            }
        }
        return view('teacher.detail_ratings');
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
