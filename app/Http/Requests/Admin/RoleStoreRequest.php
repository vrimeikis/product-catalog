<?php

declare(strict_types = 1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RoleStoreRequest
 * @package App\Http\Requests\Admin
 */
class RoleStoreRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:100|unique:roles',
            'full_access' => 'boolean',
            'description' => 'nullable|max:1000',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'name' => $this->getName(),
            'full_access' => $this->getFullAccess(),
            'accessible_routes' => $this->getAccessibleRoutes(),
            'description' => $this->getDescription(),
        ];
    }

    /**
     * @return string
     */
    private function getName(): string
    {
        return $this->input('name');
    }

    /**
     * @return bool
     */
    private function getFullAccess(): bool
    {
        return (bool)$this->input('full_access');
    }

    /**
     * @return array
     */
    private function getAccessibleRoutes(): array
    {
        return $this->input('accessible_routes', []);
    }

    /**
     * @return string|null
     */
    private function getDescription(): ?string
    {
        return $this->input('description');
    }
}
