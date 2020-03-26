<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|min:10',
            'price' => 'required|numeric|min:0.01',
            'categories' => [
                'sometimes',
                'array',
            ],
        ];
    }

}
