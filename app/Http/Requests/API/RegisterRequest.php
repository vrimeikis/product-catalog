<?php

declare(strict_types = 1);

namespace App\Http\Requests\API;

use Illuminate\Support\Facades\Hash;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\API
 */
class RegisterRequest extends LoginRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    /**
     * @return array
     */
    public function getRegisterData(): array
    {
        return [
            'name' => $this->getCustomerName(),
            'email' => $this->getCustomerEmail(),
            'password' => $this->getCustomerPassword(),
        ];
    }

    /**
     * @return string
     */
    private function getCustomerName(): string
    {
        return $this->input('name');
    }

    /**
     * @return string
     */
    private function getCustomerEmail(): string
    {
        return $this->input('email');
    }

    /**
     * @return string
     */
    private function getCustomerPassword(): string
    {
        return Hash::make((string)$this->input('password'));
    }


}
