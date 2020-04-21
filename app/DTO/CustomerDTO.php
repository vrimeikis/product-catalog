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
    private $user;

    /**
     * CustomerDTO constructor.
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
            'created' => $this->user->created_at,
            'updated' => $this->user->updated_at,
        ];
    }
}