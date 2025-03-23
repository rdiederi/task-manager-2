@extends('layouts.app')

@section('content')
    <div class="task-details">
        <h1>Task Details</h1>

        <h2>{{ $task->title }}</h2>
        <p><strong>Description:</strong> {{ $task->description }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date->format('Y-m-d') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
        <p><strong>Assigned To:</strong> {{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}</p>

        <div class="actions">
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit Task</a>

            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Task</button>
            </form>
        </div>

        <a href="{{ route('tasks.index') }}">Back to Task List</a>
    </div>
@endsection
