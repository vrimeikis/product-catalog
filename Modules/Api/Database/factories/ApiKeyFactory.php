<?php

declare(strict_types = 1);

use Faker\Generator as Faker;
use Modules\Api\Entities\ApiKey;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ApiKey::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'app_key' => $faker->unique()->uuid,
        'active' => $faker->boolean,
    ];
});
