<?php

namespace App\Http\Requests\OpenOrders;

use Illuminate\Foundation\Http\FormRequest;

class EditOpenOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'swrkc'     => ['required', 'string', 'max:255'],
            'sddte'     => ['required', 'string', 'max:255'],
            'sord'      => ['required', 'string', 'max: 255', 'unique:pgsql.fso_locals,SORD'],
            'sprod'     => ['required', 'string', 'max:255'],
            'sqreq'     => ['required', 'string', 'max:255'],
            'sqfin'     => ['required', 'string', 'max:255'],
        ];
    }
}
