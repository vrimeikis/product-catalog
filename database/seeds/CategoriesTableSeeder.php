<?php

declare(strict_types = 1);

use App\Category;
use Illuminate\Database\Seeder;

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
        factory(Category::class)->state('all')->create();
        factory(Category::class)->state('newest')->create();
        factory(Category::class)->state('most_seen')->create();
    }
}
