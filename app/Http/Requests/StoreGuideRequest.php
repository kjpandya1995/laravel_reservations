<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuideRequest extends FormRequest
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
            // 'name' => 'required|string',
            // // 'email' => 'required|email|unique:users,email',
            // // 'password' => 'required|min:6',
            // 'email' => ['required', 'email', 'unique:users,email', 'unique:user_invitations,email'], 
            'email' => [
            'required',
            'email',
            Rule::unique('user_invitations', 'email')
                ->where('company_id', $this->company->id),
        ],

        ];
    }

    public function messages(): array 
    {
        return [
            'email.unique' => 'Invitation with this email address already requested.'
        ];
    } 
}
