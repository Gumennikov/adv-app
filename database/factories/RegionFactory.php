<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Region;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->city,
        //'slug' => $faker->unique()->slug(2),
        'slug' => Str::slug($name),
        'parent_id' => null,
    ];
});
