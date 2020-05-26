<?php

declare(strict_types = 1);

namespace Modules\Administration\Tests\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Administration\Entities\Admin;
use Modules\Administration\Entities\Roles;

/**
 * Trait AuthenticateAs
 * @package Modules\Administration\Tests\Traits
 */
trait AuthenticateAs
{
    /**
     * @param array|string[] $roles
     * @param array $adminData
     * @param array $accessibleRoutes
     * @return Authenticatable
     */
    public function authenticateAs(
        array $roles = ['admin'],
        array $adminData = [],
        array $accessibleRoutes = []
    ): Authenticatable
    {
        $adminUser = factory(Admin::class)->create($adminData);

        $this->createRoles($adminUser, $roles, $accessibleRoutes);
        $this->actingAs($adminUser, 'admin');

        return $adminUser;
    }

    /**
     * @param Authenticatable $admin
     * @param array $roles
     * @param array $accessibleRoutes
     */
    private function createRoles(Authenticatable $admin, array $roles, array $accessibleRoutes)
    {
        $createdRoles = collect();
        foreach ($roles as $role) {
            $createdRoles->offsetSet(
                $role,
                factory(Roles::class)->create([
                    'name' => $role,
                    'full_access' => ($role == 'admin'),
                    'accessible_routes' => ($role == 'admin') ? [] : $accessibleRoutes,
                ])
            );
        }

        $admin->roles()->sync($createdRoles->pluck('id'));
    }
}