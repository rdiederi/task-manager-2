@extends('layouts.app')

@section('content')
    <h1>Edit Task</h1>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- This is necessary for PUT requests -->

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required class="form-control">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" required class="form-control">
        </div>

        <div class="form-group">
            <label for="due_date">Status</label>
            <select name="status" class="form-control" required>
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="in_progress">In Progress</option>
            </select>
        </div>

        @if(Auth::user()->role === 'admin')
        <div class="form-group">
                <label for="user_id">Assign User:</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <button type="submit" class="btn btn-primary p-2">Update Task</button>
    </form>

    <h2>Activity Log</h2>
    @if($task->activityLogs && $task->activityLogs->isEmpty())
        <p>No activity logs available for this task.</p>
    @elseif ($task->activityLogs)
        <ul class="list-group">
            @foreach ($task->activityLogs as $log)
                <li class="list-group-item">
                    <strong>{{ $log->action }}</strong> - {{ $log->details }} <br>
                    <small>{{ $log->created_at->format('Y-m-d H:i') }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <p>No activity logs have been initialized.</p>
    @endif

    <a href="{{ route('tasks.index') }}" class="btn btn-primary p-2">Back to Task List</a>

@endsection
