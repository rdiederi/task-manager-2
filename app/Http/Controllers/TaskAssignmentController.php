<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Mail\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskAssignmentController extends Controller
{
    public function index($task_id)
    {
        // Fetch tasks assigned to the logged-in user
        $tasks = Task::where('id', $task_id)->get();

        // Fetch all users available for reassignment
        $users = User::all();

        return view('tasks.reassign', compact('tasks', 'users'));
    }

    public function reassign(Request $request, Task $task)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Update the task's assigned user
        $task->update(['user_id' => $request->user_id]);

        // Fetch the new user to get their email
        $newUser = User::find($request->user_id);

        // Ensure the user exists and has a valid email before sending the email
        if ($newUser && filter_var($newUser->email, FILTER_VALIDATE_EMAIL)) {
            // Send the task assigned email
            Mail::to($newUser->email)->send(new TaskAssigned($task));
        } else {
            // Log an error message or handle it as needed
            return redirect()->route('tasks.reassign')->with('error', 'Invalid email address for the selected user.');
        }

        // Redirect to the main reassign page after updating the task
        return redirect()->route('tasks.index', $task->id)->with('success', 'Task reassigned successfully.');
    }
}
