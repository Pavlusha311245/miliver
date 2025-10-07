<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Traits\HasHateoasLinks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Customer */
class CustomerResource extends JsonResource
{
    use HasHateoasLinks;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '_links' => $this->generateResourceLinks('customer', $this->id),
        ];
    }
}
