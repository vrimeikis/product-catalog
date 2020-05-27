<?php

declare(strict_types = 1);

namespace Modules\Api\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AppKeyStoreRequest
 * @package Modules\Api\Http\Requests\Admin
 */
class AppKeyStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'active' => 'boolean',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->input('title');
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return (bool)$this->input('active');
    }
}
