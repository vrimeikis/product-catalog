<?php

declare(strict_types = 1);

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Modules\Administration\Entities\Admin;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Admin::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'active' => $faker->boolean,
        'remember_token' => Str::random(10),
    ];
});
