@extends('tasks.layout')

@section('code')
    <div class="card">
        <h1>Tasks</h1>
        <div class="mb-2 mt-2">
            <a href="{{ route('tasks.create') }}">Create task</a>
        </div>
        @foreach($tasks as $task)
            <div class="task-card">
                <h2>{{$task->title}}</h2>
                <p>{{$task->description}}</p>
            </div>
        @endforeach
    </div>
@endsection
