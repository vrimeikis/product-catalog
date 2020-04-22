<?php

declare(strict_types = 1);

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 * @package App\Http\Requests\API
 */
class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->only(['email', 'password']);
    }

    /**
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->input('email');
    }
}
