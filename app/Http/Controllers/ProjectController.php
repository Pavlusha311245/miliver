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
    const int PER_PAGE = 10;

    const string SORT_DIRECTION = 'asc';

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
    #[QueryParameter('direction', description: 'The sorting direction.', default: self::SORT_DIRECTION, example: 'desc')]
    #[QueryParameter('per_page', description: 'The number of items per page.', default: self::PER_PAGE, example: 5)]
    public function index(Request $request): JsonResponse
    {
        $response = [];

        $projects = Project::query();

        if ($request->has('sort')) {
            $sortField = $request->input('sort');

            $requestSortDirection = $request->has('direction') ? $request->input('direction') : self::SORT_DIRECTION;
            $sortDirection = in_array($requestSortDirection, ['asc', 'desc']) ? $requestSortDirection : self::SORT_DIRECTION;

            $projects->orderBy($sortField, $sortDirection);

            $response['sort'] = [
                'field' => $request->input('sort'),
                'direction' => $request->has('direction') ? $request->input('direction') : self::SORT_DIRECTION,
            ];
        }

        $projectsPagination = $projects->paginate($request->input('page', self::PER_PAGE));

        $response['page'] = [
            'current_page' => $projectsPagination->currentPage(),
            'per_page' => $projectsPagination->perPage(),
            'total' => $projectsPagination->total(),
            'last_page' => $projectsPagination->lastPage(),
        ];

        $response['_embedded']['projects'] = $projectsPagination->toResourceCollection();
        $response['_links'] = [
            'self' => [
                'href' => route('projects.index'),
            ],
            'create' => [
                'method' => 'POST',
                'href' => route('projects.store'),
            ],
            'next' => [
                'href' => $projectsPagination->nextPageUrl(),
            ],
            'prev' => [
                'href' => $projectsPagination->previousPageUrl(),
            ],
            'last' => [
                'href' => $projectsPagination->url($projectsPagination->lastPage()),
            ],
        ];

        return response()->json($response);
    }

    /**
     * Handles the storage of a new project resource.
     *
     * @param  ProjectRequest  $request  The incoming request containing validated project data.
     * @return JsonResponse The newly created project resource.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json(new ProjectResource($project));
    }

    /**
     * Displays the specified project resource.
     *
     * @param  Project  $project  The project instance to be displayed.
     * @return JsonResponse The specified project resource.
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json(new ProjectResource($project));
    }

    /**
     * Updates the specified project resource with validated data.
     *
     * @param  ProjectRequest  $request  The incoming request containing validated project data.
     * @param  Project  $project  The project instance to be updated.
     * @return JsonResponse The updated project resource.
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        return response()->json(new ProjectResource($project));
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
