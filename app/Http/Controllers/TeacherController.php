<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\SendToPupil;
use App\Models\ClassInSchool;
use App\Models\Pupil;
use App\Models\Message;
use App\Models\Presence;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Http\Requests\Teacher\SaveMessageRequest;
use App\Http\Requests\Teacher\SendEmailRequest;
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
        foreach ($pupils as $key => $pupil) {
            $data['users'][] = isset($pupil->user) ? $pupil->user: null;
            $data['pupils'][] = isset($pupil) ? $pupil: null;
            $data['assign_classes'] = isset($pupil->classInSchool) ? $pupil->classInSchool: null;
        }
        $teacher = Teacher::find(auth()->user()->pupil->teachers->first()->id);
        foreach($teacher->subjects as $subject) {
            $data['subject'] = $subject->id;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $data['users'],
                    'pupils' => $data['pupils'],
                    'subject' => $data['subject'],
                    'assign_classes' => $data['assign_classes'],
                    'classes_in_school' => ClassInSchool::all(),
                ]);
            }
        }
        return view('teacher.pupils');
    }

    public function updatePupils(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->pupils) && !empty($request->class_assign)) {
                Pupil::whereIn('id', explode(",", $request->pupils))->update(['class_in_school_id' => (int) $request->class_assign]);
            }
            return response()->json(['message' => 'pupils has been assign to classes']);
        }
    }

    public function savePupilTeacher(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->pupils)) {
                $teacher = auth()->user()->teacher;
                foreach ($teacher->pupils as $teacherPivot) {
                    $pupilIdPivotTable[] = $teacherPivot->id;
                }
                $diffCompare = array_diff(explode(",", $request->pupils),  $pupilIdPivotTable);
                $teacher->pupils()->attach($diffCompare);
                return response()->json(['message' => 'pupils has been assign to classes']);
            }
        }
    }

    public function savePupilSemester(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->pupils) && !empty($request->semester)) {
                $ids = explode(",", $request->pupils);
                $pupilIdPivotTable = [];
                $semesterIdPivotTable = [];
                for ($i = 0; $i < count($ids); $i++) {
                    foreach(Pupil::find($ids[$i])->semesters as $pupilPivot) {
                        $pupilIdPivotTable[] = $pupilPivot->pivot->pupil_id;
                        $semesterIdPivotTable[] = isset($pupilPivot->pivot->semester_id) ? $pupilPivot->pivot->semester_id : null;
                    }
                }
                if (!empty($ids)) {
                    $diffCompare = array_diff($ids, $pupilIdPivotTable);
                    if ($request->semester === '1') {
                        $semester1Id = in_array('1', $semesterIdPivotTable);
                        if ($semester1Id === false) {
                            for ($i = 0; $i < count($ids); $i++) {
                                Pupil::find($ids[$i])->semesters()->attach(['semester_id' => (int) $request->semester]);
                            }
                        } else {
                            for ($i = 0; $i < count($diffCompare); $i++) {
                                Pupil::find(array_values($diffCompare)[$i])->semesters()->attach(['semester_id' => (int) $request->semester]);
                            }
                        }
                    }
                    elseif ($request->semester === '2') {
                        $semester2Id = in_array('2', $semesterIdPivotTable);
                        if ($semester2Id === false) {
                            for ($i = 0; $i < count($ids); $i++) {
                                Pupil::find($ids[$i])->semesters()->attach(['semester_id' => (int) $request->semester]);
                            }
                        } else {
                            for ($i = 0; $i < count($diffCompare); $i++) {
                                Pupil::find(array_values($diffCompare)[$i])->semesters()->attach(['semester_id' => (int) $request->semester]);
                            }
                        }
                    }
                }
                return response()->json(['message' => 'pupils has been assign to classes']);
            }
        }
    }

    public function savePupilSubject(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->pupils;
            for ($i = 0; $i < count($ids); $i++) {
                foreach(Pupil::find($ids[$i])->subjects as $pupilPivot) {
                    $pupilIdPivotTable[] = $pupilPivot->pivot->pupil_id;
                    $subjectIdPivotTable[] = $pupilPivot->pivot->subject_id;
                }
            }
            $diffCompare = array_diff($ids, $pupilIdPivotTable);
            if (!empty($diffCompare)) {
                for ($i = 0; $i < count($diffCompare); $i++) {
                    Pupil::find(array_values($diffCompare)[$i])->subjects()->attach(['subject_id' => (int)$request->subject]);
                }
            }
            if (in_array($request->subject, $subjectIdPivotTable) == false) {
                for ($i = 0; $i < count($ids); $i++) {
                    Pupil::find($ids[$i])->subjects()->attach(['subject_id' => (int) $request->subject]);
                }
            }
        }
    }

    public function selectedPupils(Request $request)
    {
        foreach (auth()->user()->teacher->pupils as $pupil) {
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

    public function deletePupilTeacher(Request $request)
    {
        auth()->user()->teacher->pupils()->detach($request->selected);
        return response()->json(['message' => 'pupils has been deleted']);
    }

    public function deletePupilSemester(Request $request)
    {
        $x = 0;
        while($x < count($request->selected)) {
            Pupil::find($request->selected[$x])->semesters()->detach($request->semesters);
            $x++;
        }
        return response()->json(['message' => 'pupils has been deleted']);
    }

    public function sendMessage(SaveMessageRequest $request)
    {
        $input = $request->validated();
        if ($request->ajax()) {
            $message = Message::create([
                'teacher_id' => (int) auth()->user()->teacher->id,
                'message' => (string) $input['message'],
            ]);
            $message->pupils()->attach(explode(",", $request->selected));
            return response()->json(['message' => 'Message send successfully']);
        }
    }

    public function myMessages(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $data['teacher'] = $teacher->user->name;
        $data['myMessages'] = isset($teacher->messages) ? $teacher->messages : null;
        foreach ($data['myMessages'] as $message) {
           if ($message->id) {
               foreach ($message->pupils as $pupil) {
                   $ids[] = $pupil->id;
               }
           }
        }
        for ($i = 0; $i < count($ids); $i++) {
            $data['pupils'][] = $ids[$i];
        }
        //dd($data['pupils']);

        if ($data['myMessages']->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'teacher' => $data['teacher'],
                    'myMessages' => $this->paginate($data['myMessages']),
                    'pupils' => $data['pupils'],
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any my messages"]);
            }
        }
        return view('teacher.messages.my_messages');
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
        return view('teacher.messages.single_message');
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
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There are no any pupils."]);
            }
        }
        return view('teacher.list_emails');
    }

    public function sendEmails(SendEmailRequest $request)
    {
        $input = $request->validated();
        if ($request->ajax()) {
            foreach (explode(",", $input['selectedEmails']) as $email) {
                Mail::to($email)->send(new SendToPupil(
                    [
                        'teacher' => auth()->user()->name,
                        'message' => (string) $input['message'],
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
                    'pupils' => $data['pupils'],
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
        foreach (Pupil::find($id)->ratings as $rating) {
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
        }
    }

    public function savePupilRating(Request $request)
    {
        $pupilId = User::find($request->userId)->pupil->id;
        if ($request->ajax()) {
            Pupil::find($pupilId)->ratings()->attach(['rating_id' => $request->rating],['semester' => $request->semester]);
        }
        return response()->json(['message' => 'rating has been assign']);
    }

    public function updatePupilRating(Request $request)
    {
        if ($request->ajax()) {
            DB::table('pupil_rating')->where([
                ['pupil_id', '=', User::find($request->userId)->pupil->id],
                ['rating_id', '=', $request->dataRating],
                ['created_at', '=', str_replace("T", " ", strstr($request->dataCreate, ".", true))],
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

    public function listRatings(Request $request)
    {
        $userIds = [];
        foreach (auth()->user()->teacher->pupils as $pupil) {
            $data['users'][] = $pupil->user;
            foreach ($pupil->subjects as $subject) {
                $data['subject'] = $subject;
            }
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
        }
        return view('teacher.list_ratings');
    }

    public function detailRating(Request $request, $id)
    {
        $data = [];
        $pupil = Pupil::find(User::find($id)->pupil->id);
        $data['users'] = User::find($id);
        $data['ratings'] = $pupil->ratings;
        $data['subject'] = auth()->user()->teacher->subjects->last();
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

    public function fillPresence(Request $request)
    {
        $pupils = Pupil::with('teachers')->orderBy('user_id', 'ASC')->get();
        foreach ($pupils as $pupil) {
            $data['pupils'][] = isset($pupil) ? $pupil: null;
            $data['users'][] = isset($pupil->user) ? $pupil->user: null;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => $this->paginate($data['users']),
                    'pupils' => $data['pupils'],
                ]);
            }
        }
        return view('teacher.presence.fill_presence');
    }

    public function savePresence(Request $request)
    {
        $countDate = null;
        $userId = User::find($request->userId);
        for ($i = 0; $i < count($userId); $i++) {
            $countDate = Presence::where('pupil_id', $userId[$i]->pupil->id)
                ->whereDate('date', '=', Carbon::now()->format('Y-m-d'))->get()->count();
        }
        if ($countDate > 0) {
            return response()->json(['message' => 'presences under this date is exist']);
        } else {
            $userId = User::find($request->userId);
            for ($i = 0; $i < count($userId); $i++) {
                $pupilIds[] = $userId[$i]->pupil->id;
            }
            if ($request->ajax()) {
                for ($i = 0; $i < count($pupilIds); $i++) {
                    Presence::create([
                        'pupil_id' => $pupilIds[$i],
                        'teacher_id' => auth()->user()->teacher->id,
                        'presence' => isset($request->presence) ? (string)$request->presence : null,
                        'date' => Carbon::now()->format('Y-m-d'),
                    ]);
                }
            }
        }
        return response()->json(['message' => 'presences has been saved']);
    }

    public function detailPresence(Request $request, $id)
    {
        $user = User::find($id);
        $pupil = $user->pupil;
        $data['user'] = $user;
        $isPresences = isset($pupil->presences) ? $pupil->presences : null;
        foreach ($isPresences as $presence) {
            $data['presences'][] = $presence;
        }
        if (isset($data['presences']) && isset($data['user'])) {
            if ($request->ajax()) {
                return response()->json([
                    'user' => isset($data['user']) ? $data['user'] : null,
                    'presences' => isset($data['presences']) ? $this->paginate($data['presences'], 10) : null,
                ]);
            }
        }  else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any presences for pupils"]);
            }
        }
        return view('teacher.presence.detail_presences');
    }

    public function deletePresence(Request $request, $id)
    {
        if ($request->ajax()) {
            Presence::find($id)->delete();
        }
    }

    public function updatePresence(Request $request)
    {
        if ($request->ajax()) {
            Presence::find($request->id)->update(['presence' => $request->presence]);
        }
        return response()->json(['message' => 'presences has been updated']);
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
