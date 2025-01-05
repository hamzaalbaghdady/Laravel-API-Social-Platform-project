<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'user_name' => 'sometimes|required|min:8|max:255|unique:profiles,user_name',
            'about' => 'sometimes|required|min:20|max:5000',
            'phone_number' => 'sometimes|nullable|min:10|max:20',
            'profile_image' => 'sometimes|nullable|min:8|max:255',
            'location' => 'sometimes|nullable|max:255|',
            'education' => 'sometimes|nullable|min:8|max:255',
        ];
    }
}
