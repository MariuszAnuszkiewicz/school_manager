<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $pupil = auth()->user()->pupil;
        $messages = [];
        $teachers = [];
        foreach ($pupil->messages as $message) {
            $messages[] = $message;
            $teachers[] = Teacher::find($message->teacher_id)->user->name;
        }
        if ($request->ajax()) {
            return response()->json([
                'messages' => $this->paginate($messages),
                'teachers' => $this->paginate($teachers)
            ]);
        }
        return view('pupil.messages');
    }

    public function show(Request $request, $id)
    {
        $message = Message::where('id', $id)->first();
        $teacher = Teacher::find($message->teacher_id)->user->name;
        if ($request->ajax()) {
            return response()->json(['message' => $message, 'teacher' => $teacher]);
        }
        return view('pupil.show_message');
    }

    public function destroy($id)
    {
        Message::find($id)->delete();
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
