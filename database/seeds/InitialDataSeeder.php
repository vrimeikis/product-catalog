<?php

declare(strict_types = 1);

use Illuminate\Database\Seeder;

/**
 * Class InitialDataSeeder
 */
class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleTableSeeder::class,
        ]);
    }
}
