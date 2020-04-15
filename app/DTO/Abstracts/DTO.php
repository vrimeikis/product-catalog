<?php

declare(strict_types = 1);

namespace App\DTO\Abstracts;

use JsonSerializable;

/**
 * Class DTO
 * @package App\DTO\Abstracts
 */
abstract class DTO implements JsonSerializable
{
    /**
     * @return array
     */
    abstract protected function jsonData(): array;

    /**
     * @see JsonSerializable::jsonSerialize()
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->jsonData();
    }
}