<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = $this->authUser()->tasks()->latest();

        // Filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by due date
        if ($request->has('due_date') && $request->due_date !== 'all') {
            $today = now()->startOfDay();
            $endOfWeek = now()->endOfWeek();
            $endOfMonth = now()->endOfMonth();

            switch ($request->due_date) {
                case 'today':
                    $query->whereDate('due_date', $today);
                    break;
                case 'this_week':
                    $query->whereBetween('due_date', [$today, $endOfWeek]);
                    break;
                case 'this_month':
                    $query->whereBetween('due_date', [$today, $endOfMonth]);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', $today)
                          ->where('status', '!=', 'completed');
                    break;
                case 'no_date':
                    $query->whereNull('due_date');
                    break;
            }
        }

        $tasks = $query->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('tasks.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
        ]);

        $task = $this->authUser()->tasks()->create($validated);
        $task->refresh();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Tarefa criada com sucesso!',
                'task' => $task,
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function show(Task $task): RedirectResponse
    {
        $this->authorize('view', $task);
        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function edit(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        return redirect()->route('tasks.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,completed',
        ]);

        $task->update($validated);
        $task->refresh();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Tarefa atualizada com sucesso!',
                'task' => $task,
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tarefa excluída com sucesso!',
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }

    /**
     * Toggle the status of the specified task.
     *
     * @param Task $task
     * @return RedirectResponse|JsonResponse
     */
    public function toggleStatus(Task $task)
    {
        $this->authorize('update', $task);

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed'
        ]);

        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status da tarefa atualizado!',
                'task' => $task->fresh(),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Status da tarefa atualizado!');
    }
}
