<?php

declare(strict_types = 1);

namespace Modules\Product\Helpers;

/**
 * Class MathHelper
 * @package Modules\Product\Helpers
 */
class MathHelper
{
    /**
     * @param float $first
     * @param float $second
     * @return float
     */
    public static function percent(float $first, float $second): float
    {
        if ($first == 0) {
            return 0.0;
        }

        return ($second * 100) / $first;
    }
}