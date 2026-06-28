@extends('tasks.layout')

@section('code')
    <div class="card">
        <h1>Edit task</h1>
        <form class="form" action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf @method('PUT')
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" placeholder="Title" value="{{ old('title', $task->title) }}" required/>
            </div>
            <div class="field">
                <label for="description">Description</label>
                <input id="description" name="description" placeholder="Description"
                       value="{{ old('description', $task->description) }}"/>
            </div>
            <div class="mt-2">
                <button class="button" type="submit">Edit</button>
                <button class="button" type="button" onclick="window.location.href='{{ route('tasks.index') }}'">
                    Back
                </button>
            </div>
        </form>
    </div>
@endsection
