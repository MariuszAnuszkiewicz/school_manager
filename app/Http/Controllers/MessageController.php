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
        foreach (auth()->user()->pupil->messages as $message) {
            $data['messages'][] = $message;
            $data['teachers'][] = Teacher::find($message->teacher_id)->user->name;
        }
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'messages' => $this->paginate($data['messages']),
                    'teachers' => $data['teachers']
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There are no any messages for pupils"]);
            }
        }
        return view('pupil.messages.messages');
    }

    public function show(Request $request, $id)
    {
        $data['message'] = Message::where('id', $id)->first();
        $data['teachers'] = Teacher::find($data['message']->teacher_id)->user->name;
        if ($request->ajax()) {
            return response()->json([
                'message' => $data['message'],
                'teacher' => $data['teachers'],
            ]);
        }
        return view('pupil.messages.show_message');
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
