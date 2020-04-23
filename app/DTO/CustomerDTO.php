<?php

declare(strict_types = 1);

namespace App\DTO;

use App\DTO\Abstracts\DTO;
use App\User;

/**
 * Class CustomerDTO
 * @package App\DTO
 */
class CustomerDTO extends DTO
{
    /**
     * @var User
     */
    private $customer;

    /**
     * CustomerDTO constructor.
     * @param User $customer
     */
    public function __construct(User $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
        ];
    }
}