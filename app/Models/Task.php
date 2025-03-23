<?php

// app/Models/Task.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'status', 'user_id', 'assigned_to'];

    protected $casts = [
        'due_date' => 'datetime', // Cast date to Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function logActivity($action, $details = null)
    {
        // Create a new activity log using mass assignment
        $this->activityLogs()->create([
            'action' => $action,
            'details' => $details,
        ]);
    }
}
