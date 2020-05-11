<?php

declare(strict_types = 1);

namespace Modules\Customer\DTO;

use App\DTO\Abstracts\DTO;
use App\User;

/**
 * Class CustomerFullDTO
 * @package Modules\Customer\DTO
 */
class CustomerFullDTO extends DTO
{
    /**
     * @var User
     */
    private User $customer;

    /**
     * CustomerFullDTO constructor.
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
            'first_name' => $this->customer->name,
            'last_name' => $this->customer->last_name,
            'email' => $this->customer->email,
            'mobile' => $this->customer->mobile,
            'address' => $this->customer->address,
        ];
    }
}