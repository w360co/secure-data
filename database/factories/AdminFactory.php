<?php

namespace Database\Factories;


use Faker\Generator;
use Illuminate\Support\Str;
use W360\SecureData\Models\Admin;

/** @var \Illuminate\Database\Eloquent\Factory $factory */


$factory->define(Admin::class, function (Generator $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'identifier' => '110101001',
        'salary' => $faker->randomFloat(10),
        'status' => true,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(Admin::class, 'unverified', [
    'email_verified_at' => null
]);
