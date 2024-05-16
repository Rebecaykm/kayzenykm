<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrinterRequest extends FormRequest
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
            'brand' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'ip' => ['nullable', 'string'],
            'port' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'workcenters' => ['nullable', 'array'],
            'workcenters.*' => ['exists:workcenters,id'],
        ];
    }
}