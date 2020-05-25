<?php

declare(strict_types = 1);

namespace Modules\Administration\Tests\Traits;

use App\Admin;
use App\Roles;
use Illuminate\Contracts\Auth\Authenticatable;

trait AuthenticateAs
{
    public function authenticateAs(
        array $roles = ['admin'],
        array $adminData = [],
        array $accessibleRoutes = []
    )
    {
        $adminUser = factory(Admin::class)->create($adminData);

        $this->createRoles($adminUser, $roles);

        $this->actingAs($adminUser, 'admin');
    }

    private function createRoles(Authenticatable $admin, array $roles)
    {
        $createdRoles = collect();
        foreach ($roles as $role) {
            $createdRoles->offsetSet(
                $role,
                factory(Roles::class)->create([
                    'name' => $role,
                    'full_access' => ($role == 'admin'),
                ])
            );
        }

        $admin->roles()->sync($createdRoles->pluck('id'));
    }
}