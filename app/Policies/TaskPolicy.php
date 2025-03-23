<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskPolicy
{
    public function view(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->role === 'admin';
    }

    public function update(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->role === 'admin';
    }

    public function manage(Task $task)
    {
        return Auth::user()->id === $task->user_id || Auth::user()->role === 'admin';
    }
}
