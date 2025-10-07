<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Project;
use App\Traits\HasHateoasLinks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    use HasHateoasLinks;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '_links' => $this->generateResourceLinks('project', $this->id),
        ];
    }
}
