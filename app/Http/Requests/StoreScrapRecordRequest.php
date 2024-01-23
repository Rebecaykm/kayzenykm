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
            'quantity' => ['required', 'integer', 'min:1', 'max:1000']
        ];
    }

    public function messages()
    {
        return [
            'production_plan_id.required' => 'Debes tener un plan.',
            'production_plan_id.numeric' => 'Debes tener un plan.',
            'part_number_id.required' => 'Debes selecionar un número de parte.',
            'part_number_id.numeric' => 'Debes selecionar un número de parte.',
            'scrap_id.numeric' => 'Debes selecionar un Tipo de Scrap',
            'scrap_id.required' => 'Debes selecionar un Tipo de Scrap',
            'quantity.required' => 'Debes ingresar una cantidad validad.',
            'quantity.min' => 'La cantidad no puede ser negativo o cero.',
            'quantity.max' => 'La cantidad no puede ser mayor a 1000.',
        ];
    }
}
