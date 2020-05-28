<?php

declare(strict_types = 1);

namespace Modules\Api\Http\Requests\Admin;

/**
 * Class AppKeyUpdateRequest
 * @package Modules\Api\Http\Requests\Admin
 */
class AppKeyUpdateRequest extends AppKeyStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return parent::rules();
    }
}
