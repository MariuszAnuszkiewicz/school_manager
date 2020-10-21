<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $view = [];
        if ($request->isMethod('get')) {
            $view['events'] = Event::all();
        }
        return view('pupil.events', [
            'events' => $view['events']
        ]);
    }
}
