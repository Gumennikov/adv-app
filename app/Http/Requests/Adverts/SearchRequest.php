<?php


namespace App\Http\Requests\Adverts;


class SearchRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => 'nullable|string',
            'attrs.*.equals' => 'nullable|string',
            'attrs.*.from' => 'nullable|numeric',
            'attrs.*.to' => 'nullable|numeric',
        ];
    }
}
