<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        $parentId = $this->route('parent') ? $this->route('parent')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:parents,email,'.$parentId,
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ];
    }
}
