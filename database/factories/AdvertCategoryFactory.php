<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\Adverts\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->name,
        'slug' => Str::slug($name),
        'parent_id' =>null,
    ];
});
