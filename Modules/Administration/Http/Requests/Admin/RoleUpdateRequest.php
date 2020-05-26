<?php

declare(strict_types = 1);

namespace Modules\Administration\Http\Requests\Admin;

use Illuminate\Validation\Rule;

/**
 * Class RoleUpdateRequest
 * @package Modules\Administration\Http\Requests\Admin
 */
class RoleUpdateRequest extends RoleStoreRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('roles')->ignoreModel($this->route()->parameter('role')),
            ],
            'full_access' => 'boolean',
            'description' => 'nullable|max:1000',
        ];
    }
}
