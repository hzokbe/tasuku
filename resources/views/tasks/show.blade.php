@extends('tasks.layout')

@section('code')
    <div class="task-card mx-4">
        <h1>{{$task->title}}</h1>
        <p>{{$task->description}}</p>
    </div>
    <div class="mt-2 mx-4 flex gap-2">
        <button type="button" onclick="window.location.href='{{ route('tasks.edit', $task) }}'">Edit task</button>
        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
            @csrf @method('DELETE')
            <button class="destroy-button" type="submit">Destroy task</button>
        </form>
        <button type="button" onclick="window.location.href='{{ route('tasks.index') }}'">Back</button>
    </div>
@endsection
