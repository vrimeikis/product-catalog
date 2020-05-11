<?php

declare(strict_types = 1);

namespace Modules\Customer\Services;

use App\User;
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
     * @throws CustomerException
     */
    public function updateMyInfoApi(array $data): void
    {
        $customer = $this->getAuthUser();

        $this->updateInfo($data, $customer->id);
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
    public function deleteMe(): void
    {
        $customer = $this->getAuthUser();

        $this->customerRepository->delete($customer->id);
    }
}