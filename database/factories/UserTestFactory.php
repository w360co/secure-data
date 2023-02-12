<?php

namespace Database\Factories;

use Faker\Generator;
use W360\ImageStorage\Models\UserTest;
/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(UserTest::class, function (Generator $faker) {
    return [
        'name' => 'Administrator',
        'uid' => 'WEGYhxxZDdeIXc8HxSOXJGQC8gt1',
    ];
});
