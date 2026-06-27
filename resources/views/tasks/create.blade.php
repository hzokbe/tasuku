@extends('tasks.layout')

@section('code')
    <div class="card">
        <h1>Create task</h1>
        <form class="form" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" placeholder="Title" required/>
            </div>
            <div class="field">
                <label for="description">Description</label>
                <input id="description" name="description" placeholder="Description"/>
            </div>
            <div class="mt-2">
                <button type="submit">Create</button>
                <button type="button" onclick="window.location.href='{{ route('tasks.index') }}'">Back</button>
            </div>
        </form>
    </div>
@endsection
