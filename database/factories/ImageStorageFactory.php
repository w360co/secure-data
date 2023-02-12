<?php

namespace Database\Factories;

use Faker\Generator;
use W360\ImageStorage\Models\ImageStorage;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(ImageStorage::class, function (Generator $faker) {
    return [
        'name' => $faker->slug(4).".jpg",
        'author' => 'Elbert Tous',
        'storage' => $faker->slug(2),
    ];
});
