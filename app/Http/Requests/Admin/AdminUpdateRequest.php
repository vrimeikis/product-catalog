<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Class AdminUpdateRequest
 *
 * @package App\Http\Requests\Admin
 */
class AdminUpdateRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($this->route()->parameter('admin')->id),
            ],
            'password' => 'nullable|string|confirmed|min:8',
            'active' => 'boolean',
            'roles' => 'sometimes|array',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'name' => $this->getName(),
            'last_name' => $this->getLastName(),
            'email' => $this->getEmail(),
            'active' => $this->getActive(),
        ];

        if (!empty($this->input('password'))) {
            $data['password'] = $this->getPass();
        }

        return $data;
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
     * @return null|string
     */
    public function getPass(): ?string
    {
        $password = $this->input('password');

        if (!empty($password)) {
            return Hash::make($password);
        }

        return null;
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
