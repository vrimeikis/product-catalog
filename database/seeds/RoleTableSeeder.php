<?php

declare(strict_types = 1);

use App\Roles;
use Illuminate\Database\Seeder;

/**
 * Class RoleTableSeeder
 */
class RoleTableSeeder extends Seeder
{
    /**
     * Super Admin role name
     */
    const ADMIN_ROLE = 'Super Admin';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->createRole(self::ADMIN_ROLE, [], 'Has access to all routes', true);
        $this->createRole('Moderator');
    }

    /**
     * @param string $name
     * @param array $accessibleRoutes
     * @param string|null $description
     * @param bool $fullAccess
     */
    private function createRole(
        string $name,
        array $accessibleRoutes = [],
        ?string $description = null,
        bool $fullAccess = false
    ): void
    {
        $exists = Roles::query()->where('name', '=', $name)->exists();
        if ($exists) {
            return;
        }

        factory(Roles::class)->create([
            'name' => $name,
            'full_access' => $fullAccess,
            'accessible_routes' => $accessibleRoutes,
            'description' => $description,
        ]);
    }
}
