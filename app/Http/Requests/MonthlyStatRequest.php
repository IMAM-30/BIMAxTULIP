<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyStatRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'year'  => 'required|integer|min:2000|max:3000',
            'month' => 'required|integer|min:1|max:12',
            'value' => 'required|integer|min:0',
            'label' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ];
    }
}
