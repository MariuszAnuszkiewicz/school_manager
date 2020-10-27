<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request, Event $event)
    {
        $this->authorize('index', $event);
        $events = Event::with('teachers')->latest()->paginate(5);
        $teachers = [];
        foreach ($events as $eventHandle) {
            foreach ($eventHandle->teachers as $teacher) {
               $teachers[] = $teacher->user->name;
            }
        }
        if ($request->ajax()) {
            return response()->json(['events' => $events, 'teachers' => $teachers]);
        }
        return view('pupil.events');
    }

    public function destroy($id)
    {
        Event::find($id)->delete();
    }
}
