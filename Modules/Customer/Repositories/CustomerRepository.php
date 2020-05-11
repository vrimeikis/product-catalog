<?php

declare(strict_types = 1);

namespace Modules\Customer\Repositories;

use App\User;
use Modules\Core\Repositories\Repository;

/**
 * Class CustomerRepository
 * @package Modules\Customer\Repositories
 */
class CustomerRepository extends Repository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }
}