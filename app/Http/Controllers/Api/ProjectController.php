<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * GET /api/projects
     * Lista todos os projetos
     */
    public function index(): JsonResponse
    {
        $projects = Project::withCount('tasks')->get();

        return response()->json($projects, 200);
    }

    /**
     * POST /api/projects
     * Cria um novo projeto
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json($project, 201);
    } 

    /**
     * GET /api/projects/{id}/tasks
     * Exibe as tarefas de um projeto específico
     */
    public function tasks(Project $project): JsonResponse
    {
        $tasks = $project->tasks()->orderBy('created_at', 'desc')->get();

        return response()->json($tasks, 200);
    }

    /**
     * PUT/PATCH /api/projects/{id}
     * Atualiza um projeto específico.
     */
    public function update(Project $project, StoreProjectRequest $request): JsonResponse
    {
        $project->update($request->validated());

        return response()->json($project, 200);
    }

    /**
     * DELETE /api/projects/{id}
     * Remove um projeto específico.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }
}
