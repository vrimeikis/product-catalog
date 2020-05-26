<?php

declare(strict_types = 1);

namespace Modules\Administration\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AdminStoreRequest
 * @package Modules\Administration\Http\Requests\Admin
 */
class AdminStoreRequest extends FormRequest
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
            'name' => 'nullable|string|max:30',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|unique:admins|max:255',
            'password' => 'required|string|confirmed|min:8',
            'active' => 'boolean',
            'roles' => 'sometimes|array',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'name' => $this->getName(),
            'last_name' => $this->getLastName(),
            'email' => $this->getEmail(),
            'password' => $this->getPass(),
            'active' => $this->getActive(),
        ];
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->input('name');
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->input('last_name');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->input('email');
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->input('password');
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return (bool)$this->input('active');
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->input('roles', []);
    }

}
