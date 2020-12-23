<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Pupil;
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
        } else {
            if ($request->ajax()) {
                return response()->json(['message' => 'There are no any events.']);
            }
        }
        return view('pupil.events');
    }

    public function destroy($id)
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
