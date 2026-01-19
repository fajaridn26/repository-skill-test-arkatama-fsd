<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'owner_id' => ['required', 'exists:owners,id'],
            'raw_hewan' => ['required', 'string', 'max:255'],

            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:100'],

            'age' => ['required', 'integer', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0.1'],
        ];
    }
}
