<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use Modules\Product\Entities\Product;
use Modules\Product\Enum\ProductTypeEnum;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'slug' => $faker->unique()->slug,
        'type' => $faker->randomElement(ProductTypeEnum::enum())->id(),
        'price' => $faker->randomFloat(2, 1),
        'description' => $faker->sentence,
        'active' => $faker->boolean,
    ];
});
