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
            'quantity' => ['required']
        ];
    }
}
