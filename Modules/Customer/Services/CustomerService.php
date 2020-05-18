<?php

declare(strict_types = 1);

namespace Modules\Customer\Services;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\DTO\CustomerFullDTO;
use Modules\Customer\Exceptions\CustomerException;
use Modules\Customer\Repositories\CustomerRepository;

/**
 * Class CustomerService
 * @package Modules\Customer\Services
 */
class CustomerService
{
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * CustomerService constructor.
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }


    /**
     * @return CustomerFullDTO
     * @throws CustomerException
     */
    public function getMyInfoApi(): CustomerFullDTO
    {
        $customer = $this->getAuthUser();

        return new CustomerFullDTO($customer);
    }

    /**
     * @param array $data
     * @return int
     * @throws CustomerException
     */
    public function updateMyInfoApi(array $data): int
    {
        $customer = $this->getAuthUser();

        return $this->updateInfo($data, $customer->id);
    }

    /**
     * @param array $data
     * @param int $id
     * @return int
     */
    public function updateInfo(array $data, int $id): int
    {
        return $this->customerRepository->update($data, $id);
    }

    /**
     * @return User
     * @throws CustomerException
     */
    private function getAuthUser(): User
    {
        /** @var User $customer */
        $customer = auth()->user();

        if (!$customer instanceof User) {
            throw CustomerException::noCustomer();
        }

        return $customer;
    }

    /**
     * @throws CustomerException
     */
    public function deleteMe(): int
    {
        $customer = $this->getAuthUser();

        return $this->customerRepository->delete($customer->id);
    }

    /**
     * @param array $data
     * @return User|Model
     * todo: write test
     */
    public function create(array $data): User
    {
        return $this->customerRepository->create($data);
    }
}