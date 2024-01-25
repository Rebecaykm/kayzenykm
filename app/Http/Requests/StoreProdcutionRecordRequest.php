<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdcutionRecordRequest extends FormRequest
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
            // 'production_id' => ['required', 'numeric'],
            // 'part_number_id' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric', 'min:1', 'max:1000'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start']
        ];
    }

    public function messages()
    {
        return [
            'quantity.min' => 'La cantidad no puede ser negativo o cero.',
            'quantity.max' => 'La cantidad no puede ser mayor a 1000.',
            'time_end.time_start' => 'La hora inicio es necesario',
            'time_end.time_end' => 'La hora fin es necesario',
            'time_end.after' => 'La Hora de Fin no puede ser menor a la Hora de Inicio',
        ];
    }
}
