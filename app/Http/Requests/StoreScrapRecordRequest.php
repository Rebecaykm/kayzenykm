<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScrapRecordRequest extends FormRequest
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
            'production_plan_id' => ['required', 'numeric'],
            'part_number_id' => ['required', 'numeric'],
            'scrap_id' => ['required', 'numeric'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99']
        ];
    }

    public function messages()
    {
        return [
            'production_plan_id.required' => 'Selecciona un plan de producción.',
            'production_plan_id.numeric' => 'El plan de producción debe ser un número válido.',
            'part_number_id.required' => 'Selecciona un número de parte.',
            'part_number_id.numeric' => 'El número de parte debe ser un número válido.',
            'scrap_id.required' => 'Selecciona un tipo de scrap.',
            'scrap_id.numeric' => 'El tipo de scrap debe ser un número válido.',
            'quantity.required' => 'Ingresa una cantidad válida.',
            'quantity.min' => 'La cantidad debe ser mayor que cero.',
            'quantity.max' => 'La cantidad no puede ser mayor a 99.',

        ];
    }
}
