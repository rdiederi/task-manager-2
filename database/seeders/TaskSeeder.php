<?php

// database/seeders/TaskSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Retrieve all users
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                // Create random tasks for each user
                Task::create([
                    'title' => "Task {$i} for " . $user->name,
                    'description' => "Description for Task {$i}.",
                    'due_date' => Carbon::today()->addDays(rand(1, 10)), // Random due date in the next 10 days
                    'status' => 'pending',
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
