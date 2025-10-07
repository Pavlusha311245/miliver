<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * Controller for handling project-related operations.
 */
class ProjectController extends Controller
{
    /**
     * Display a collection of all projects.
     *
     * @param  Request  $request  The incoming request.
     * @return JsonResponse The collection of projects.
     *
     * @throws Throwable
     */
    #[QueryParameter('page', description: 'The page number to retrieve.', default: 1)]
    #[QueryParameter('sort', description: 'The field to sort by.', default: 'id')]
    #[QueryParameter('direction', description: 'The sorting direction.', default: self::DEFAULT_SORT_DIRECTION, example: 'desc')]
    #[QueryParameter('per_page', description: 'The number of items per page.', default: self::PER_PAGE, example: 5)]
    public function index(Request $request): JsonResponse
    {
        $response = [];

        $projectsQuery = Project::query();

        if ($request->has('sort')) {
            $sortField = $request->input('sort');

            $requestSortDirection = $request->has('direction') ? $request->input('direction') : self::DEFAULT_SORT_DIRECTION;
            $sortDirection = in_array($requestSortDirection, ['asc', 'desc']) ? $requestSortDirection : self::DEFAULT_SORT_DIRECTION;

            $projectsQuery->orderBy($sortField, $sortDirection);

            $response['sort'] = [
                'field' => $request->input('sort'),
                'direction' => $request->has('direction') ? $request->input('direction') : self::DEFAULT_SORT_DIRECTION,
            ];
        }

        $projects = $projectsQuery->paginate($request->input('page', self::PER_PAGE));

        $response['page'] = [
            'current_page' => $projects->currentPage(),
            'per_page' => $projects->perPage(),
            'total' => $projects->total(),
            'last_page' => $projects->lastPage(),
        ];

        $response['_embedded']['projects'] = $projects->toResourceCollection();
        $response['_links'] = $this->generateCollectionLinks($projects, 'project');

        return response()->json($response);
    }

    /**
     * Creates a new project resource.
     *
     * @param  ProjectRequest  $request  The incoming request containing validated project data.
     * @return ProjectResource The newly created project resource.
     */
    public function store(ProjectRequest $request): ProjectResource
    {
        return new ProjectResource(Project::create($request->validated()));
    }

    /**
     * Displays the specified project resource.
     *
     * @param  Project  $project  The project instance to be displayed.
     * @return ProjectResource The specified project resource.
     */
    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    /**
     * Updates the specified project resource with validated data.
     *
     * @param  ProjectRequest  $request  The incoming request containing validated project data.
     * @param  Project  $project  The project instance to be updated.
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
     * @param  Project  $project  The project instance to be deleted.
     * @return JsonResponse A JSON response indicating the result of the deletion.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully.',
            '_links' => [
                'list' => [
                    'href' => route('projects.index'),
                ],
                'create' => [
                    'method' => 'POST',
                    'href' => route('projects.store'),
                ],
            ],
        ]);
    }
}
