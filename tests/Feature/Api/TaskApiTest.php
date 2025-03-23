<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication
        $this->user = User::factory()->create(); // Make sure you have a User factory
    }

    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email']]);
    }

    /** @test */
    public function user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /** @test */
    public function authenticated_user_can_create_task()
    {
        $token = $this->user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson('/api/tasks', [
                             'title' => 'New Task',
                             'description' => 'Task description',
                             'status' => 'pending',
                             'due_date' => '2023-12-31',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'description', 'status', 'due_date']);
    }

    /** @test */
    public function authenticated_user_can_view_tasks()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $token = $this->user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $task->title]);
    }

    /** @test */
    public function authenticated_user_can_update_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $token = $this->user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->putJson('/api/tasks/' . $task->id, [
                             'title' => 'Updated Task',
                             'description' => 'Updated description',
                             'status' => 'completed',
                             'due_date' => '2024-01-01',
                         ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $token = $this->user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->deleteJson('/api/tasks/' . $task->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /** @test */
    public function authenticated_user_can_reassign_task()
    {
        $originalUser = User::factory()->create();
        $newUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $originalUser->id]);

        $token = $originalUser->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/tasks/{$task->id}/reassign", [
            'user_id' => $newUser->id
        ]);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'user_id' => $newUser->id,
                    'id' => $task->id,
                ]);
    }

}
