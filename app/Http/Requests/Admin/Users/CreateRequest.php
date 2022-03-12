<?php

namespace App\Http\Requests\Admin\Users;

use App\Entity\User;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Admin\Users
 * @property User $user
 */
class CreateRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ];
    }
}
