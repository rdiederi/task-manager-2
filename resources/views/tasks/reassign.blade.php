@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Reassign Tasks</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($tasks->isEmpty())
        <div class="alert alert-info">You have no tasks to reassign.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Current Assignee</th>
                    <th>Reassign To</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->user->name }}</td>
                        <td>
                            <form action="{{ route('tasks.reassign.perform', $task) }}" method="POST">
                                @csrf
                                <select name="user_id" class="form-control" required>
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-warning mt-2">Reassign</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
