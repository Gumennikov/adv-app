<?php


namespace App\Http\Requests\Adverts;


class RejectRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => 'required|string',
        ];
    }
}
