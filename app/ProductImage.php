<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductImage
 * @package App
 */
class ProductImage extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'file',
    ];
}
