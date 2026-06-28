@extends('tasks.layout')

@section('code')
    <div class="card">
        <h1>Tasks</h1>
        <div class="mb-2 mt-2">
            <a class="button" href="{{ route('tasks.create') }}">Create task</a>
        </div>
        @foreach($tasks as $task)
            <div class="flex items-center task-card">
                <div class="flex-1">
                    <h2>
                        <a href="{{ route('tasks.show', $task) }}">{{$task->title}}</a>
                    </h2>
                    <p>{{$task->description}}</p>
                </div>
                <div class="flex gap-2">
                    <a class="button" href="{{ route('tasks.edit', $task) }}">Edit task</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="destroy-button" type="submit">Destroy task</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
