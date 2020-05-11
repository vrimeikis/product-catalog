<?php

declare(strict_types = 1);

namespace Modules\Core\DTO;

use JsonSerializable;

/**
 * Class DTO
 * @package Modules\Core\DTO
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