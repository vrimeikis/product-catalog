<?php

declare(strict_types = 1);

use Faker\Generator as Faker;
use Modules\ContactUs\Entities\ContactMessage;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ContactMessage::class, function (Faker $faker) {
    return [
        'client_name' => $faker->name,
        'client_email' => $faker->safeEmail,
        'message' => $faker->sentence(),
    ];
});
