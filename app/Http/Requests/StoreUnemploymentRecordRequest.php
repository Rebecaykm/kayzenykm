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
            'time_start' => ['required', 'date_format:Y-m-d\TH:i'],
            'time_end' => ['required', 'date_format:Y-m-d\TH:i', 'after:time_start']
        ];
    }

    public function messages()
    {
        return [
            'workcenter_id.required' => 'Selecciona una estación de trabajo.',
            'workcenter_id.numeric' => 'La estación de trabajo debe ser un número.',
            'unemployment_id.required' => 'Selecciona un motivo de paro.',
            'unemployment_id.numeric' => 'El motivo de paro debe ser un número.',
            'time_start.required' => 'La hora de inicio es obligatoria.',
            'time_end.required' => 'La hora de finalización es obligatoria.',
            'time_end.after' => 'La hora de finalización debe ser posterior a la hora de inicio.',
        ];
    }
}
