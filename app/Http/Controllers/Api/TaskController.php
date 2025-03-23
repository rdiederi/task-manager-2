<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest; // Create request for validation
use App\Models\Task;
use Illuminate\Http\Request;
use App\Mail\TaskAssigned;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)->get();
        return response()->json($tasks);
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated() + ['user_id' => $request->user()->id]);
        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }

    public function reassign(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $task->user_id = $request->user_id;
        $task->save();

        // Fetch the new user to get their email
        $newUser = User::find($request->user_id);

        if ($newUser && filter_var($newUser->email, FILTER_VALIDATE_EMAIL)) {
            // Send the task assigned email
            Mail::to($newUser->email)->send(new TaskAssigned($task));
        } else {
            // Log an error message or handle it as needed
            return response()->json([
                'message' => 'Invalid email address for the selected user.'
            ],403);
        }

        return response()->json([
            'message' => 'Task reassigned successfully.',
            'task' => $task,
        ]);
    }
}
