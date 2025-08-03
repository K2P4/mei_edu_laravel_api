<?php

namespace App\Http\Requests\Volunteer;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'image' => 'nullable',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'team' => 'nullable|string|max:20',
            'batch' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ];
    }
}
