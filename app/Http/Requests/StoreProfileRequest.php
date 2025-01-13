<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'user_name' => 'required|min:8|max:255|unique:profiles,user_name',
            'about' => 'required|min:20|max:5000',
            'phone_number' => 'nullable|min:10|max:20',
            'profile_image' => 'nullable|min:8|max:255',
            'location' => 'nullable|max:255|',
            'education' => 'nullable|min:8|max:255',
        ];
    }
}
