<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    protected $task;

    /**
     * Create a new message instance.
     *
     * @param $task
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tasks.assigned') // Your email view could be structured in resources/views/emails/tasks/assigned.blade.php
            ->with([
                'taskTitle' => $this->task->title,
                'taskDescription' => $this->task->description,
                'taskStatus' => $this->task->status,
            ]);
    }
}
