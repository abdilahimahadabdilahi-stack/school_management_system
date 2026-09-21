<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:parents,email',
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ];
    }
}
