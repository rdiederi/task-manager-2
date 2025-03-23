<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $tasks = Task::with('user');

        // Filter by title/ description
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $tasks->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $tasks->where('status', $request->status);
        }

        // Filter by due date
        if ($request->has('due_date') && !empty($request->due_date)) {
            $tasks->whereDate('due_date', $request->due_date);
        }

        // Get the filtered tasks
        $tasks = $tasks->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all(); // Get all users for the dropdown

        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id', // Validate the user exists
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => Auth::id(), // The user creating the task
            'status' => 'pending',
            'assigned_to' => $request->assigned_to, // Save the assigned user
        ]);

        $task->logActivity('created', "Task '{$task->title}' created.");

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load('activityLogs');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = User::all(); // Get all users for the dropdown

        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id', // Ensure the assigned user exists
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to, // Update the assigned user
        ]);

        $task->logActivity('updated', "Task '{$task->title}' updated.");

        // Check if the status has changed to log it specifically
        if ($request->has('status') && $request->status !== $task->getOriginal('status')) {
            $task->logActivity('status changed', "Status changed to '{$request->status}'.");
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function assign(Request $request, Task $task)
    {
        // Only admins can assign tasks to others
        $this->authorize('manage', $task);

        $request->validate(['user_id' => 'required|exists:users,id']);
        $task->user_id = $request->user_id; // Assign task to the specified user
        $task->save();

        return back()->with('success', 'Task Assigned!');
    }
}
