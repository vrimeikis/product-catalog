<?php

declare(strict_types = 1);

namespace Modules\Customer\Events\API\Contracts;

/**
 * Interface CustomerAuthContract
 * @package Modules\Customer\Events\API\Contracts
 */
interface CustomerAuthContract
{
    /**
     * @return string
     */
    public function getType(): string;
}