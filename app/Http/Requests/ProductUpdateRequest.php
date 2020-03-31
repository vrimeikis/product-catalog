<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Product;

/**
 * Class ProductUpdateRequest
 *
 * @package App\Http\Requests
 */
class ProductUpdateRequest extends ProductStoreRequest
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
        return parent::rules();
    }

    /**
     * @return bool
     */
    protected function slugExists(): bool {
        return Product::query()
            ->where('slug', '=', $this->getSlug())
            ->where('id', '!=', $this->route()->parameter('product')->id)
            ->exists();
    }
}
