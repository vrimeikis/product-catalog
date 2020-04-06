<?php

declare(strict_types = 1);

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CategoriesTableSeeder
 */
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'created_at' => $now,
                'updated_at' => $now,
                'title' => 'All',
                'slug' => 'all',
            ],
            [
                'created_at' => $now,
                'updated_at' => $now,
                'title' => 'Newest',
                'slug' => 'newest',
            ],
            [
                'created_at' => $now,
                'updated_at' => $now,
                'title' => 'Most seen',
                'slug' => 'most-seen',
            ],
        ]);
    }
}
