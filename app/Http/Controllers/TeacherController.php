<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\SendToPupil;
use App\Models\ClassInSchool;
use App\Models\Pupil;
use App\Models\Message;
use App\Models\Presence;
use App\Models\User;
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
    public function index(Request $request) : Object
    {
        $pupils = Pupil::with('teachers')->orderBy('user_id', 'ASC')->get();
        foreach ($pupils as $pupil) {
            $data['users'][] = !is_null($pupil->user) ? $pupil->user: false;
            $data['pupils'][] = !is_null($pupil) ? $pupil: false;
            $data['assign_classes'] = !is_null($pupil->classInSchool) ? $pupil->classInSchool: false;
        }
        foreach(auth()->user()->teacher->subjects as $subject) {
            $data['subject'] = !is_null($subject->id) ? $subject->id : false;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => isset($data['users']) ? $data['users'] : null,
                    'pupils' => isset($data['pupils']) ? $data['pupils'] : null,
                    'subject' => isset($data['subject']) ? $data['subject'] : null,
                    'assign_classes' => isset($data['assign_classes']) ? $data['assign_classes'] : null,
                    'classes_in_school' => !is_null(ClassInSchool::all()) ? ClassInSchool::all() : false,
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
            $data['users'][] = !is_null($pupil->user) ? $pupil->user : false;
            $data['pupils'][] = !is_null($pupil) ? $pupil : false;
            $data['assign_classes'][] = !is_null($pupil->classInSchool) ? $pupil->classInSchool : false;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => isset($data['users']) ? $data['users'] : null,
                    'pupils' => isset($data['pupils']) ? $data['pupils'] : null,
                    'assign_classes' => isset($data['assign_classes']) ? $data['assign_classes'] : null,
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
        $data['teacher'] = !is_null($teacher->user->name) ? $teacher->user->name : false;
        $data['myMessages'] = !is_null($teacher->messages) ? $teacher->messages : false;
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
        if ($data['myMessages']->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'teacher' => isset($data['teacher']) ? $data['teacher'] :null,
                    'myMessages' => isset($data['myMessages']) ? $this->paginate($data['myMessages']) : false,
                    'pupils' => isset($data['pupils']) ? $data['pupils'] : false,
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any messages"]);
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
                    'message' => isset($data['message']) ? $data['message'] : null,
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any messages"]);
            }
        }
        return view('teacher.messages.single_message');
    }

    public function listEmails(Request $request) : Object
    {
        foreach(auth()->user()->teacher->pupils as $pupil) {
            $users[] = $pupil->user;
        }
        foreach($users as $user) {
            $data['pupils'][] = $user;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'pupils' => isset($data['pupils']) ? $this->paginate($data['pupils']) : false,
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
        $pupils = !is_null($teacher->pupils) ? $teacher->pupils : null;
        foreach($pupils as $pupil) {
            $data['users'][] = !is_null($pupil->user) ? $pupil->user : false;
            $data['pupils'][] = !is_null($pupil) ? $pupil : false;
        }
        $data['subjects'] = $teacher->subjects;
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => isset($data['users']) ? $this->paginate($data['users'], 7) : false,
                    'pupils' => isset($data['pupils']) ? $data['pupils'] : null,
                    'subjects' => isset($data['subjects']) ? $data['subjects'] : null,
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
        $ratings = !is_null(Pupil::find($id)->ratings) ? Pupil::find($id)->ratings : false;
        foreach ($ratings as $rating) {
            $data['ratings'][] = !is_null($rating->rating) ? $rating->rating : false;
            $data['createAt'][] = !is_null($rating->pivot->created_at) ? $rating->pivot->created_at : false;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'ratings' => isset($data['ratings']) ? $data['ratings'] : null,
                    'createAt' => isset($data['createAt']) ? $data['createAt'] : null,
                ]);
            }
        }
    }

    public function savePupilRating(Request $request)
    {
        $pupilId = !is_null(User::find($request->userId)->pupil->id) ? User::find($request->userId)->pupil->id : false;
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
            $data['users'][] = !is_null($pupil->user) ? $pupil->user : false;
            foreach ($pupil->subjects as $subject) {
                $data['subject'] = !is_null($subject) ? $subject : false;
            }
            foreach ($pupil->ratings as $key => $rating) {
                $userIds[$pupil->user->id][] = !is_null($rating->rating) ? $rating->rating : null;
            }
        }
        $gradeList = [];
        foreach ($userIds as $key => $ids) {
            $gradeList[$key] = implode(", ", $ids);
        }
        $data['ratings'] = !is_null($gradeList) ? $gradeList : false;
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => isset($data['users']) ? $this->paginate($data['users'], 7) : false,
                    'ratings' => isset($data['ratings']) ? $data['ratings'] : null,
                    'subject' => isset($data['subject']) ? $data['subject'] : null,
                ]);
            }
        }
        return view('teacher.list_ratings');
    }

    public function detailRating(Request $request, $id)
    {
        $data = [];
        $pupil = !is_null(Pupil::find(User::find($id)->pupil->id)) ? Pupil::find(User::find($id)->pupil->id) : false;
        $data['users'] = !is_null(User::find($id)) ? User::find($id) : false;
        $data['ratings'] = !is_null($pupil->ratings) ? $pupil->ratings : false;
        $data['subject'] = auth()->user()->teacher->subjects->last();
        foreach ($data['ratings'] as $rating) {
            $data['created_at'][] = !is_null($rating->pivot->created_at) ? $rating->pivot->created_at : false;
        }
        if (!empty($data['ratings'] && !empty($data['created_at']))) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => isset($data['users']) ? $data['users'] : null,
                    'ratings' => isset($data['ratings']) ? $data['ratings'] : null,
                    'subject' => isset($data['subject']) ? $data['subject'] : null,
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
            $data['pupils'][] = !is_null($pupil) ? $pupil : false;
            $data['users'][] = !is_null($pupil->user) ? $pupil->user: false;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'users' => isset($data['users']) ? $this->paginate($data['users']) : false,
                    'pupils' => isset($data['pupils']) ? $data['pupils'] : null,
                ]);
            }
        }
        return view('teacher.presence.fill_presence');
    }

    public function savePresence(Request $request)
    {
        $countDate = null;
        $userId = !is_null(User::find($request->userId)) ? User::find($request->userId) : false;
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
                        'pupil_id' => isset($pupilIds[$i]) ? $pupilIds[$i] : null,
                        'teacher_id' => isset(auth()->user()->teacher->id) ? auth()->user()->teacher->id : null,
                        'presence' => isset($request->presence) ? (string) $request->presence : null,
                        'date' => !is_null(Carbon::now()->format('Y-m-d')) ? Carbon::now()->format('Y-m-d') : null,
                    ]);
                }
            }
        }
        return response()->json(['message' => 'presences has been saved']);
    }

    public function detailPresence(Request $request, $id)
    {
        $user = !is_null(User::find($id)) ? User::find($id) : false;
        $pupil = !is_null($user->pupil) ? $user->pupil : false;
        $data['user'] = !is_null($user) ? $user : false;
        $isPresences = !is_null($pupil->presences) ? $pupil->presences : false;
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
