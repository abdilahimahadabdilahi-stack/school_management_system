<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManagerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'manager']);
    }

    public function rules(): array
    {
        $managerId = $this->route('manager') ? $this->route('manager')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:managers,email,'.$managerId,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
        ];
    }
}
