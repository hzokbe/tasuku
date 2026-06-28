@extends('tasks.layout')

@section('code')
    <div class="task-card mx-4">
        <h1>{{$task->title}}</h1>
        <p>{{$task->description}}</p>
    </div>
    <div class="mt-2 mx-4">
        <button type="button" onclick="window.location.href='{{ route('tasks.edit', $task) }}'">Edit task</button>
        <button type="button" onclick="window.location.href='{{ route('tasks.index') }}'">Back</button>
    </div>
@endsection
