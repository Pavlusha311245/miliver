<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '_links' => [
                'self' => [
                    'href' => route('projects.show', ['project' => $this->id]),
                ],
                'update' => [
                    'method' => 'PUT',
                    'href' => route('projects.update', ['project' => $this->id]),
                ],
                'delete' => [
                    'method' => 'DELETE',
                    'href' => route('projects.destroy', ['project' => $this->id]),
                ],
                'list' => [
                    'href' => route('projects.index'),
                ],
            ],
        ];
    }
}
