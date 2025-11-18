<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if ($this->isMethod('put')) {
            return [
                'name' => 'required',
                'cedula' => [
                    'nullable',
                    'regex:/^\d{6,20}$/',
                    Rule::unique('customers', 'cedula')->ignore($this->route('customer')->id ?? null),
                ],
                'address' => 'nullable|max:255',
                'job' => 'nullable|string',
                'birthdate' => 'nullable|date',
                'gender' => 'required|in:Male,Female',
            ];
        }

        return [
            'name' => 'required',
            'cedula' => ['nullable', 'regex:/^\d{6,20}$/', 'unique:customers,cedula'],
            'address' => 'nullable|max:255',
            'job' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|unique:users,email',
        ];
    }
}
