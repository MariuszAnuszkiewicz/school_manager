<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request, Event $event)
    {
        $this->authorize('index', $event);
        if ($request->ajax()) {
            return response()->json(['events' => Event::latest()->paginate(5)]);
        }
        return view('pupil.events');
    }

    public function destroy($id)
    {
        Event::find($id)->delete();
    }
}
