<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer',
        ];
    }
}
