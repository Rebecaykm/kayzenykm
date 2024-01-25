<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnemploymentRecordRequest extends FormRequest
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
            'workcenter_id' => ['required', 'numeric'],
            'unemployment_id' => ['required', 'numeric'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start']
        ];
    }

    public function messages()
    {
        return [
            'workcenter_id.required' => 'Debes seleccionar una estación de trabajo.',
            'workcenter_id.numeric' => 'Debes seleccionar una estación de trabajo.',
            'unemployment_id.required' => 'Debes seleccionar un paro.',
            'unemployment_id.numeric' => 'Debes selecionar un número de parte.',
            'time_start.required' => 'La hora inicio es necesaria',
            'time_end.required' => 'La hora fin es necesaria',
            'time_end.after' => 'La Hora Fin no puede ser menor a la Hora Inicio',
        ];
    }
}
