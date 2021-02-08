<?php

namespace App\Http\Controllers;

use App\Models\LessonPlan;
use App\Models\ClassInSchool;
use App\Models\User;
use App\Models\Pupil;
use App\Models\Teacher;
use App\Models\Subject;
use App\Http\Requests\Admin\SearchUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $data['pupils'] = Pupil::all()->count();
        $data['teachers'] = Teacher::all()->count();
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'pupils' => isset($data['pupils']) ? $data['pupils'] : null,
                    'teachers' => isset($data['teachers']) ? $data['teachers'] : null,
                ]);
            }
        }else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any data"]);
            }
        }
        return view('admin.index');
    }

    public function assignSubjectsForTeachers(Request $request) : Object
    {
        $data['teachers'] = Teacher::all();
        $data['subjects'] = Subject::all();
        foreach ($data['teachers'] as $teacher) {
            $data['users'][] = User::find($teacher->user_id);
            $data['subjectAssign'] = Teacher::find($teacher->id)->subjects;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'teachers' => isset($data['teachers']) ? $data['teachers'] : null,
                    'subjects' => isset($data['subjects']) ? $data['subjects'] : null,
                    'subjectAssign' => isset($data['subjectAssign']) ? $data['subjectAssign'] : null,
                    'users' => isset($data['users']) ? $data['users'] : null,
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any data"]);
            }
        }
        return view('admin.assign_subject_for_teacher');
    }

    public function subjectAssign(Request $request)
    {
        if ($request->ajax()) {
            Teacher::find($request->teacher)->subjects()->update(['subject_id' => (int) $request->subject_assign]);
        }
        return response()->json(['message' => 'Subject has been updated successfully.']);
    }

    public function searchUser()
    {
        return view('admin.user.search_user');
    }

    public function searchRun(SearchUserRequest $request) : Object
    {
        $input = $request->validated();
        if ($request->ajax()) {
              return User::with('roles')->where('name', 'LIKE', '%' . $input['search'] . '%')
                  ->orWhere('email', 'LIKE', '%' . $input['search'] . '%')->get();
        }
    }

    public function createLessonPlan(Request $request, int $id) : Object
    {
        $data['lessonPlan'] = LessonPlan::where('class_in_school_id', (int) $id)->get();
        $data['subjects'] = Subject::all();
        if ($data['lessonPlan']->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'lessonPlan' => $data['lessonPlan'],
                    'subjects' => $data['subjects'],
                    'nameClass' => ClassInSchool::find((int) $id),
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => 'There are no any lesson plan.']);
            }
        }
        return view('admin.lesson_plan.create_lesson_plan');
    }

    public function updateLessonPlan(Request $request)
    {
        (int) $x = 0;
        while ($x < count((array) $request->data)) {
            $data[] = explode("|", (string) $request->data[$x]);
            $x++;
        }
        for ($i = 0; $i < count((array) $data); $i++) {
            $day[] = $data[$i][1];
            $subject[] = $data[$i][2];
        }
        $updateSlot = array_combine($day, $subject);
        if ($request->ajax()) {
            DB::table('lessons_plans')->where([
                ['hours', '=', $data[0][0]],
            ])->update($updateSlot);
        }
        return response()->json(['message' => 'lesson plan has been updated']);
    }

    public function saveLessonPlan(Request $request)
    {
        if ($request->ajax()) {
            $hours = [
               '8:00 - 8:45',
               '8:55 - 9:40',
               '9:50 - 10:35',
               '10:45 - 11:30',
               '11:50 - 12:35',
               '12:45 - 13:30',
               '13:40 - 14:25',
               '14:35 - 15:20',
               '15:30 - 16:15',
            ];
            foreach ($hours as $hour) {
                LessonPlan::create([
                   'class_in_school_id' => (int) $request->class_in_school_id,
                   'hours' => $hour,
                ]);
            }
        }
        return response()->json(['message' => 'lesson plan has been saved']);
    }
}
