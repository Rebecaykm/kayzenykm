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
            'quantity' => ['required', 'numeric', 'min:1', 'max:9999'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start']
        ];
    }

    public function messages()
    {
        return [
            'quantity.min' => 'La cantidad debe ser mayor que cero.',
            'quantity.max' => 'La cantidad no puede ser mayor a 1000.',
            'time_end.time_start' => 'La hora de inicio es necesaria.',
            'time_end.time_end' => 'La hora de finalización es necesaria.',
            'time_end.after' => 'La hora de finalización debe ser posterior a la hora de inicio.',
        ];
    }
}
