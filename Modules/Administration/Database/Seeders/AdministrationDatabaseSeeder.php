<?php

declare(strict_types = 1);

namespace Modules\Administration\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class AdministrationDatabaseSeeder
 * @package Modules\Administration\Database\Seeders
 */
class AdministrationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
    }
}
