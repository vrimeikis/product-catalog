<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminStoreRequest
 *
 * @package App\Http\Requests\Admin
 */
class AdminStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'name' => 'nullable|string|max:30',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|unique:admins|max:255',
            'password' => 'required|string|confirmed|min:8',
            'active' => 'boolean',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array {
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
    private function getName(): ?string {
        return $this->input('name');
    }

    /**
     * @return string|null
     */
    private function getLastName(): ?string {
        return $this->input('last_name');
    }

    /**
     * @return string
     */
    private function getEmail(): string {
        return $this->input('email');
    }

    /**
     * @return string
     */
    private function getPass(): string {
        return Hash::make($this->input('password'));
    }

    /**
     * @return bool
     */
    private function getActive(): bool {
        return (bool)$this->input('active');
    }

}
