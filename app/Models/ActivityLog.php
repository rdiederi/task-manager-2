<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'task_id', // Include the foreign key
        'action',  // Allow mass assignment for action
        'details', // Optionally include details if you have it
    ];
}
