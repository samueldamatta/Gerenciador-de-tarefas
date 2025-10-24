<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{

    /**
     * POST /api/tasks
     * Cria uma nova tarefa
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        $task->load('project');

        return response()->json($task, 201);
    }

    /**
     * PUT/PATCH /api/tasks/{id}
     * Atualiza uma tarefa específica.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());

        $task->refresh();
        $task->load('project');

        return response()->json($task, 200);
    }

    /**
     * DELETE /api/tasks/{id}
     * Remove uma tarefa específica.
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
