@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Dashboard</h1>

    <div class="mt-4">
        <h2>Welcome, {{ Auth::user()->name }}!</h2>
        <p>Here’s how to use the Task Management App:</p>
        <ul>
            <li><strong>Create a Task:</strong> Click on the "Create Task" button to add a new task to your list.</li>
            <li><strong>View Your Tasks:</strong> Navigate to the "Tasks" section to see all tasks assigned to you.</li>
            <li><strong>Edit Tasks:</strong> Click on the "Edit" button next to a task to modify its details.</li>
            <li><strong>Delete Tasks:</strong> Remove tasks you no longer need by clicking the "Delete" button next to each task.</li>
        </ul>
    </div>
    <div class="mt-4">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New Task</a>
    </div>
</div>
@endsection
