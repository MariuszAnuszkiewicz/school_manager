<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Pupil;
use App\Models\LessonPlan;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class EventController extends Controller
{
    public function index(Request $request, Event $event)
    {
        $this->authorize('index', $event);
        foreach (Pupil::find(auth()->user()->pupil->id)->teachers as $teacher) {
            $data['events'] = $teacher->events;
        }
        $x = 0;
        while ($x < count($teacher->events)) {
            $data['teachers'][] = $teacher->user->name;
            $x++;
        }
        if (count($data['events']) > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'events' => $this->paginate($data['events']),
                    'teachers' => $data['teachers']
                ]);
            }
        }
        return view('pupil.events');
    }

    public function destroy($id)
    {
        Event::find($id)->delete();
    }

    public function eventsByCalendar(Request $request)
    {
        $data['teacher'] = auth()->user()->teacher;
        $hoursColumn = explode(" - ", LessonPlan::pluck('hours'));
        foreach ($hoursColumn as $key => $hour) {
            if ($key > 0) {
                $data['start'][] = substr(strstr($hour, ','), 2, strlen($hour));
            } else {
                $data['start'][] = substr(LessonPlan::pluck('hours')->first(), 0, strlen($hour) - 2);
            }
        }
        foreach ($hoursColumn as $key => $hour) {
            if ($key === count($hoursColumn) - 1) {
                $data['end'][] = substr($hour, 0, strlen($hour) - 2);
            } else {
                $data['end'][] = substr($hour, 0, strlen(strstr($hour, ',')) - 2);
            }
        }
        unset($data['start'][count($data['start']) - 1]);
        unset($data['end'][0]);
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'teacher' => $data['teacher'],
                    'start' => array_values($data['start']),
                    'end' => array_values($data['end']),
                ]);
            }
        }
        return view('teacher.event.create_event');
    }

    public function listEventsByTeacher(Request $request)
    {
        $data['events'] = auth()->user()->teacher->events->toArray();
        rsort($data['events']);
        if (!empty($data)) {
            if ($request->ajax()) {
                return response()->json([
                    'events' => $this->paginate($data['events'])
                ]);
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => "There aren't any events for pupils"]);
            }
        }
        return view('teacher.event.create_event');
    }

    public function saveEvents(Request $request)
    {
        if ($request->ajax()) {
            $event = Event::create([
                'title' => $request->event,
                'date' => $request->date,
                'start' => $request->start,
                'end' => $request->end,
            ]);
        }
        return response()->json(['message' => $event->id]);
    }

    public function saveEventTeacher(Request $request)
    {
        if ($request->ajax()) {
            Event::find($request->eventId)->teachers()->attach(['teacher_id' => $request->teacherId]);
        }
        return response()->json(['message' => 'event has been create']);
    }

    public function updateEvent(Request $request)
    {
        Event::find($request->id)->update(['title' => $request->title]);
        return response()->json(['message' => 'event has been update']);
    }

    public function deleteEventByTeacher($id)
    {
        Event::find($id)->delete();
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ? : 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
