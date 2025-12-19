<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can create a task via API (AJAX).
     */
    public function test_user_can_create_task_via_ajax(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/tasks', [
                'title' => 'Nova Tarefa',
                'description' => 'Descrição da tarefa',
                'priority' => 'high',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'task' => ['id', 'title', 'description', 'priority', 'status'],
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nova Tarefa',
            'user_id' => $user->id,
            'priority' => 'high',
            'status' => 'pending',
        ]);
    }

    /**
     * Test that a user can only see their own tasks.
     */
    public function test_user_can_only_see_own_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Tarefa User 1',
        ]);

        Task::factory()->create([
            'user_id' => $user2->id,
            'title' => 'Tarefa User 2',
        ]);

        $response = $this->actingAs($user1)->get('/tasks');

        $response->assertStatus(200)
            ->assertSee('Tarefa User 1')
            ->assertDontSee('Tarefa User 2');
    }

    /**
     * Test that a user can toggle task status via AJAX.
     */
    public function test_user_can_toggle_task_status_via_ajax(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/tasks/{$task->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'task' => ['id', 'status'],
            ])
            ->assertJson([
                'success' => true,
                'task' => ['status' => 'completed'],
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test that a user can update a task via AJAX.
     */
    public function test_user_can_update_task_via_ajax(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Tarefa Original',
            'priority' => 'low',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/tasks/{$task->id}", [
                '_method' => 'PUT',
                'title' => 'Tarefa Atualizada',
                'description' => 'Nova descrição',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->addDays(5)->format('Y-m-d'),
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'task' => ['id', 'title', 'description', 'priority'],
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Tarefa Atualizada',
            'priority' => 'high',
        ]);
    }

    /**
     * Test that a user can delete a task via AJAX.
     */
    public function test_user_can_delete_task_via_ajax(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test that a user cannot access another user's task.
     */
    public function test_user_cannot_access_another_user_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)
            ->postJson("/tasks/{$task->id}", [
                '_method' => 'PUT',
                'title' => 'Tentativa de Edição',
                'priority' => 'high',
                'status' => 'pending',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test task validation when creating.
     */
    public function test_task_creation_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/tasks', [
                'description' => 'Sem título',
                'priority' => 'high',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test task filtering by status.
     */
    public function test_user_can_filter_tasks_by_status(): void
    {
        $user = User::factory()->create();

        $pendingTask = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'title' => 'Tarefa Pendente',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'title' => 'Tarefa Concluída',
        ]);

        $response = $this->actingAs($user)
            ->get('/tasks?status=pending');

        $response->assertStatus(200);
        // Should see pending task title
        $response->assertSee('Tarefa Pendente');
        // Should not see completed task
        $response->assertDontSee('Tarefa Concluída');
    }
}
