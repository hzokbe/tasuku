@extends('tasks.layout')

@section('code')
    <div class="card">
        <h1>Tasks</h1>
        @foreach($tasks as $task)
            <div class="task-card">
                <h2>{{$task->title}}</h2>
                <p>{{$task->description}}</p>
            </div>
        @endforeach
    </div>
@endsection
