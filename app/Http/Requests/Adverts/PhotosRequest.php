<?php


namespace App\Http\Requests\Adverts;


class PhotosRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files.*' => 'required|image|mimes:jpg,jpeg,png',
        ];
    }
}
