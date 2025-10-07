<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'date_of_birth' => ['nullable'],
            'email' => ['nullable', 'email', 'max:254'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
