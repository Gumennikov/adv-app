<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $active = $faker->boolean;
    $phoneActive = $faker->boolean;
    return [
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $active ? now() : null,
        'phone_verified' => $phoneActive,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'status' => $active ? User::STATUS_ACTIVE : User::STATUS_WAIT,
        'role' => $active ? $faker->randomElement([User::ROLE_USER, User::ROLE_ADMIN]) : User::ROLE_USER,
        'verify_token' => $active ? null : Str::uuid(),
        'phone_verify_token' => $phoneActive ? null : Str::uuid(),
        'phone_verify_token_expire' => $phoneActive ? null : Carbon::now()->addSeconds(300),
    ];
});
