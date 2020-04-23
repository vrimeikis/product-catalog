<?php

declare(strict_types = 1);

namespace App\DTO;

use App\DTO\Abstracts\DTO;
use App\User;

/**
 * Class CustomerMiniDTO
 * @package App\DTO
 */
class CustomerMiniDTO extends DTO
{
    /**
     * @var User
     */
    private User $user;


    /**
     * CustomerMiniDTO constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'name' => $this->user->name,
            'email' => $this->user->email,
        ];
    }
}