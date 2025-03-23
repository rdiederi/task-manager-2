<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize()
    {
        return true; // You can add additional logic here for permissions
    }

    // Define the validation rules
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed,in_progress', // Adjust if you have specific status values
            'due_date' => 'nullable|date',
        ];
    }
}
