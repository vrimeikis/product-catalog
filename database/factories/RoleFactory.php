<?php

declare(strict_types = 1);

use App\Roles;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Roles::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->words(3, true),
        'full_access' => $faker->boolean,
        'accessible_routes' => [],
        'description' => $faker->text,
    ];
});

$factory->state(Roles::class, 'super_admin', function (Faker $faker) {
    return [
        'name' => 'Super Admin',
        'full_access' => true,
        'description' => 'Has all permissions to routes.',
    ];
});

$factory->state(Roles::class, 'moderator', function (Faker $faker) {
    return [
        'name' => 'Moderator',
        'full_access' => false,
        'description' => null,
    ];
});
