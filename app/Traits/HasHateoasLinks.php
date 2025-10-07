<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasHateoasLinks
{
    public function generateCollectionLinks(LengthAwarePaginator $paginator, $resourceName): array
    {
        return [
            'self' => [
                'href' => route("{$resourceName}s.index"),
            ],
            'create' => [
                'method' => 'POST',
                'href' => route("{$resourceName}s.store"),
            ],
            'next' => [
                'href' => $paginator->nextPageUrl(),
            ],
            'prev' => [
                'href' => $paginator->previousPageUrl(),
            ],
            'last' => [
                'href' => $paginator->url($paginator->lastPage()),
            ],
        ];
    }

    public function generateResourceLinks($resourceName, $resourceId): array
    {
        return [
            'self' => [
                'href' => route("{$resourceName}s.show", ["$resourceName" => $resourceId]),
            ],
            'update' => [
                'method' => 'PUT',
                'href' => route("{$resourceName}s.update", ["$resourceName" => $resourceId]),
            ],
            'delete' => [
                'method' => 'DELETE',
                'href' => route("{$resourceName}s.destroy", ["$resourceName" => $resourceId]),
            ],
            'list' => [
                'href' => route("{$resourceName}s.index"),
            ],
        ];
    }
}
