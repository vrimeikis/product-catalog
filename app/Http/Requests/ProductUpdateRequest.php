<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProductUpdateRequest
 *
 * @package App\Http\Requests
 */
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
            'active' => 'nullable|boolean',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'active' => $this->getActive(),
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->input('title');
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->input('description');
    }

    /**
     * @return float
     */
    public function getPrice(): float {
        return (float)$this->input('price', 0.01);
    }

    /**
     * @return bool
     */
    public function getActive(): bool {
        return (bool)$this->input('active');
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->input('categories', []);
    }

}
