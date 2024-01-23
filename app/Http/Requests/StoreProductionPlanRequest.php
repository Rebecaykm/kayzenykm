<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionPlanRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'partNumber' => ['required'],
            'planQuantity' => ['required', 'integer', 'min:1'],
            'date' => ['required'],
            'shift' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'planQuantity.min' => 'La cantidad del plan no puede ser negativo o cero',
        ];
    }
}
