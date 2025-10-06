<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * Display a collection of all projects.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection(Project::all());
    }

    /**
     * Handles the storage of a new project resource.
     *
     * @param ProjectRequest $request The incoming request containing validated project data.
     * @return ProjectResource The newly created project resource.
     */
    public function store(ProjectRequest $request): ProjectResource
    {
        return new ProjectResource(Project::create($request->validated()));
    }

    /**
     * Displays the specified project resource.
     *
     * @param Project $project The project instance to be displayed.
     * @return ProjectResource The specified project resource.
     */
    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    /**
     * Updates the specified project resource with validated data.
     *
     * @param ProjectRequest $request The incoming request containing validated project data.
     * @param Project $project The project instance to be updated.
     * @return ProjectResource The updated project resource.
     */
    public function update(ProjectRequest $request, Project $project): ProjectResource
    {
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    /**
     * Deletes the specified project resource.
     *
     * @param Project $project The project instance to be deleted.
     * @return JsonResponse A JSON response indicating the result of the deletion.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json();
    }
}
