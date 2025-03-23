@extends('layouts.app')

@section('content')
<h1 class="mb-4">Task List</h1>

@auth
    <div class="mb-3">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('tasks.index') }}">
        <div class="form-row mb-4">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Search by title or description" value="{{ request('search') }}">
            </div>
            <div class="col">
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                </select>
            </div>
            <div class="col">
                <input type="date" name="due_date" class="form-control" value="{{ request('due_date') }}">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @if ($tasks->isEmpty())
        <div class="alert alert-info">No tasks found.</div>
    @else
        <div class="list-group">
            @foreach ($tasks as $task)
                <div class="list-group-item">
                    <h5 class="mb-1">{{ $task->title }}</h5>
                    <p class="mb-1">{{ $task->description }}</p>
                    <small>Assigned To: {{ $task->user->name }}</small><br>
                    <small>Status: {{ $task->status }}</small><br>
                    <small>Due: {{ $task->due_date }} | Status: {{ $task->status }}</small>

                    <div class="mt-2">
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        @endcan

                        @can('delete', $task)
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endcan

                        <a href="{{ route('tasks.reassign', $task->id) }}" class="btn btn-sm btn-primary">Reassign Task</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endauth

@guest
    <div class="alert alert-warning">You need to log in to view your tasks. <a href="{{ route('login') }}">Login here</a>.</div>
@endguest

@endsection
