@extends('template.master')
@section('title', 'User')
@section('content')
    <div class="container">
        <h1>User Activity Log</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>By</th>
                    <th>Logged At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->causer->name ?? 'System' }}</td>
                        <td>{{ Helper::dateFormatTime($activity->created_at) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
