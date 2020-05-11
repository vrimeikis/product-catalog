<?php

declare(strict_types = 1);

namespace Modules\Customer\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CustomerUpdateRequest
 * @package Modules\Customer\Http\Requests\API
 */
class CustomerUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|max:255|string',
            'last_name' => 'nullable|max:50|string',
            'email' => [
                'required',
                'max:255',
                'email',
                Rule::unique('users')->ignore($this->user('api')->id),
            ],
            'mobile' => 'nullable|max:20',
            'address' => 'nullable|max:255',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function getData(): array
    {
        return [
            'name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'email' => $this->getEmail(),
            'mobile' => $this->getMobile(),
            'address' => $this->getAddress(),
        ];
    }

    private function getFirstName(): string
    {
        return $this->input('first_name');
    }

    private function getLastName(): ?string
    {
        return $this->input('last_name');
    }

    private function getEmail(): string
    {
        return $this->input('email');
    }

    private function getMobile(): ?string
    {
        return $this->input('mobile');
    }

    private function getAddress(): ?string
    {
        return $this->input('address');
    }


}
