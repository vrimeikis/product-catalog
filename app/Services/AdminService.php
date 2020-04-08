<?php

declare(strict_types = 1);

namespace App\Services;

use App\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    /**
     * @param string $email
     * @param string $password
     * @param bool $active
     * @param array $additionalData
     * @return Admin|Model
     */
    public function create(string $email, string $password, bool $active = false, array $additionalData = []): Admin
    {
        $data = [
            'email' => $email,
            'password' => Hash::make($password),
            'active' => $active,
        ];

        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }

        return Admin::query()->create($data);
    }
}