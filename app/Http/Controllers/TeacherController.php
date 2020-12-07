<?php

namespace App\Http\Controllers;

use App\Mail\SendToPupil;
use App\Models\ClassInSchool;
use App\Models\Pupil;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

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
                    'pupils' => $data['pupils'],
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

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
