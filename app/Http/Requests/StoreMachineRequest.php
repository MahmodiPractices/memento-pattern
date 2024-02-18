<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMachineRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'unique:machines,name'],
            'core' => ['required', 'numeric', 'min:1', 'max:12'],
            'ram' => ['required', 'numeric', 'min:2', 'max:8'],
            'storage' => ['required', 'numeric', 'min:10', 'max:100'],
        ];
    }
}
