<?php

declare(strict_types = 1);

namespace App\DTO;

use App\DTO\Abstracts\DTO;
use App\Product;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductDTO
 * @package App\DTO
 */
class ProductDTO extends DTO
{
    /**
     * @var Product
     */
    private $product;

    /**
     * ProductDTO constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'title' => $this->product->title,
            'slug' => $this->product->slug,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'images' => $this->getImages(),
        ];
    }

    private function getImages(): array
    {
        $images = [];

        foreach ($this->product->images as $image) {
            $images[] = Storage::url($image->file);
        }

        return $images;
    }
}