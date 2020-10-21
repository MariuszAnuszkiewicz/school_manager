@extends('layouts.app')
@section('title')
    Events
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Day</th>
                            <th>Hour Start</th>
                            <th>Hour End</th>
                        </tr>
                        </thead>
                        @foreach ($events as $event)
                            <tbody>
                                <tr>
                                    <td class="text-center yellow-td">{{ $event->title }}</td>
                                    <td class="text-center green-td">{{ $event->day }}</td>
                                    <td class="text-center blue-td">{{ $event->hour_start }}</td>
                                    <td class="text-center">{{ $event->hour_end }}</td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
