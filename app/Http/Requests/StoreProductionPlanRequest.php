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
            'partNumber' => ['required', 'numeric'],
            'planQuantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'date' => ['required', 'after_or_equal:today'],
            'shift' => ['required', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'partNumber.numeric' => 'Selecciona un número de parte válido.',
            'planQuantity.required' => 'Ingresa una cantidad válida.',
            'planQuantity.min' => 'La cantidad debe ser mayor que cero.',
            'planQuantity.max' => 'La cantidad no puede ser mayor a 1000.',
            'date.required' => 'Selecciona una fecha.',
            'date.after_or_equal' => 'La fecha debe ser igual o posterior al día de hoy.',
            'shift.numeric' => 'Selecciona un turno válido.',
        ];
    }
}
